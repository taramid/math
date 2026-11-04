<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\AuthToken;
use App\Entity\Student;
use App\Repository\AuthTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

/**
 * Authenticates requests by the "auth_token" cookie.
 *
 * The cookie holds a raw random token; only its sha256 hash is stored
 * in the database (AuthToken::hash).
 *
 * @see https://symfony.com/doc/current/security/custom_authenticator.html
 */
class CookieAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AuthTokenRepository $authTokenRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        return $request->cookies->has(AuthCookieConfig::NAME);
    }

    public function authenticate(Request $request): Passport
    {
        $raw = (string) $request->cookies->get(AuthCookieConfig::NAME);
        $hash = hash('sha256', $raw);

        $token = $this->authTokenRepository->findValidByHash($hash);
        if (null === $token) {
            // Invalid or expired cookie: the request continues anonymously.
            throw new CustomUserMessageAuthenticationException('Invalid or expired auth cookie');
        }

        return new SelfValidatingPassport(
            new UserBadge($token->getStudent()->getUserIdentifier()),
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        // Stay anonymous instead of returning 401: public pages (e.g. /hello)
        // must keep working with a broken or missing cookie.
        return null;
    }

    /**
     * Called when an anonymous request hits a protected route (e.g. /go):
     * create an account, set the cookie and land on the intermediate page.
     *
     * We intentionally do NOT redirect to /go from the same request cycle:
     * the browser must issue a fresh request that already carries the new
     * cookie (avoids race conditions).
     */
    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        $raw = bin2hex(random_bytes(32));

        $student = new Student();
        $token = new AuthToken();
        $token->setHash(hash('sha256', $raw));
        $token->setExpiresAt(new \DateTimeImmutable('+'.AuthCookieConfig::MAX_AGE));
        $token->setStudent($student);

        $this->entityManager->persist($student);
        $this->entityManager->persist($token);
        $this->entityManager->flush();

        $response = new Response(status: Response::HTTP_FOUND, headers: ['Location' => $this->urlGenerator->generate('app_created')]);
        $response->headers->setCookie(new Cookie(
            name: AuthCookieConfig::NAME,
            value: $raw,
            expire: (int) new \DateTimeImmutable('+'.AuthCookieConfig::MAX_AGE)->format('U'),
            path: AuthCookieConfig::PATH,
            secure: AuthCookieConfig::SECURE,
            httpOnly: AuthCookieConfig::HTTP_ONLY,
            sameSite: AuthCookieConfig::SAME_SITE,
        ));

        return $response;
    }
}
