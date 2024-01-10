<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Ecommit\CrudBundle\Entity\UserCrudInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface, UserCrudInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 70)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 70)]
    private ?string $firstName = null;

    #[ORM\Column(type: 'string', length: 70)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 70)]
    private ?string $lastName = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $password = null;

    #[Assert\NotBlank(groups: ['EditUser'])]
    #[Assert\PasswordStrength(minScore: Assert\PasswordStrength::STRENGTH_MEDIUM, groups: ['EditUser'])]
    #[Assert\Length(min: 12, max: 4096, groups: ['EditUser'])]
    public ?string $plainPassword = null;

    #[ORM\Column(type: 'date')]
    #[Assert\NotBlank]
    private ?\DateTime $birthDate = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $enabled = false;

    /**
     * @var string[]
     */
    #[ORM\Column(type: 'json')]
    protected ?array $roles = [];

    /*
     * Methodes
     */

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return $roles;
    }

    /**
     * @return string[]
     */
    public static function getAvailableRoles(): array
    {
        return [
            'ROLE_USER_MANAGER' => 'role.user_manager',
        ];
    }

    public function eraseCredentials(): void
    {
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /*
     * Evenements
     */

    #[ORM\PrePersist]
    public function onPrePersist(PrePersistEventArgs $eventArgs): void
    {
        if (null === $this->createdAt) {
            $this->createdAt = new \DateTime('now');
        }
    }

    /*
     * Getters / Setters (auto-generated)
     */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setBirthDate(?\DateTime $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getBirthDate(): ?\DateTime
    {
        return $this->birthDate;
    }

    public function setCreatedAt(?\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setEnabled(?bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }
}
