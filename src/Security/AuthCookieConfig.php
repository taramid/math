<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\HttpFoundation\Cookie;

final class AuthCookieConfig
{
    public const string NAME = 'auth_token';
    public const string PATH = '/';
    public const string MAX_AGE = '399 days';
    public const string RENEW_WHEN_LESS_THAN = '366 days';
    public const bool HTTP_ONLY = true;
    public const bool SECURE = true;
    public const string SAME_SITE = Cookie::SAMESITE_LAX;
}
