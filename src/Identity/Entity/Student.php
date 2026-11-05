<?php

namespace App\Identity\Entity;

use App\Identity\StudentRepository;
use App\Identity\UserRole;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    /**
     * @var Collection<int, AuthToken>
     */
    #[ORM\OneToMany(targetEntity: AuthToken::class, mappedBy: 'student')]
    private Collection $authTokens;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->authTokens = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->id;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return [UserRole::Student->value];
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return Collection<int, AuthToken>
     */
    public function getAuthTokens(): Collection
    {
        return $this->authTokens;
    }

    public function addAuthToken(AuthToken $authToken): static
    {
        if (!$this->authTokens->contains($authToken)) {
            $this->authTokens->add($authToken);
            $authToken->setStudent($this);
        }

        return $this;
    }

    public function removeAuthToken(AuthToken $authToken): static
    {
        if ($this->authTokens->removeElement($authToken)) {
            // set the owning side to null (unless already changed)
            if ($authToken->getStudent() === $this) {
                $authToken->setStudent(null);
            }
        }

        return $this;
    }
}
