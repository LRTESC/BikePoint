<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\JoinTable(name: 'course_user_favorite')]
    #[ORM\JoinColumn(name: 'course_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $userFavorites;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 100)]
    private ?string $title = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 5000)]
    private ?string $description = null;

    #[ORM\Column(type: 'string', length: 10)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 10)]
    private ?string $zipCode = null;

    #[ORM\Column(type: 'string', length: 45)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 45)]
    private ?string $city = null;

    #[ORM\ManyToOne(targetEntity: Level::class)]
    #[ORM\JoinColumn(name: 'level_id', referencedColumnName: 'id', nullable: false)]
    #[Assert\NotBlank]
    private ?Level $level = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'author_id', referencedColumnName: 'id', nullable: false)]
    private ?User $author = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(type: 'integer', length: 500000)]
    #[Assert\NotBlank]
    #[Assert\Range(min: 2, max: 500000)]
    private ?int $distance = null;

    #[ORM\Column(type: 'integer', length: 300000)]
    #[Assert\NotBlank]
    #[Assert\Range(min: 2, max: 300000)]
    private ?int $positiveAscent = null;

    #[ORM\Column(type: 'integer', length: 300000)]
    #[Assert\NotBlank]
    #[Assert\Range(min: 2, max: 300000)]
    private ?int $maximumAltitude = null;

    #[ORM\Column(type: 'integer', length: 100)]
    #[Assert\NotBlank]
    #[Assert\Range(min: 2, max: 1000)]
    private ?int $negativeGradient = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    #[Assert\Range(min: 2, max: 1000)]
    private ?int $minimumAltitude = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $fileMimeType = null;

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
     * Methodes
     */

    public function hasImage(): bool
    {
        return null !== $this->fileMimeType;
    }

    /*
     * Getters / Setters (auto-generated)
     */

    public function __construct()
    {
        $this->userFavorites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setZipCode(?string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
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

    public function setDistance(?int $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    public function getDistance(): ?int
    {
        return $this->distance;
    }

    public function setPositiveAscent(?int $positiveAscent): self
    {
        $this->positiveAscent = $positiveAscent;

        return $this;
    }

    public function getPositiveAscent(): ?int
    {
        return $this->positiveAscent;
    }

    public function setMaximumAltitude(?int $maximumAltitude): self
    {
        $this->maximumAltitude = $maximumAltitude;

        return $this;
    }

    public function getMaximumAltitude(): ?int
    {
        return $this->maximumAltitude;
    }

    public function setNegativeGradient(?int $negativeGradient): self
    {
        $this->negativeGradient = $negativeGradient;

        return $this;
    }

    public function getNegativeGradient(): ?int
    {
        return $this->negativeGradient;
    }

    public function setMinimumAltitude(?int $minimumAltitude): self
    {
        $this->minimumAltitude = $minimumAltitude;

        return $this;
    }

    public function getMinimumAltitude(): ?int
    {
        return $this->minimumAltitude;
    }

    public function setFileMimeType(?string $fileMimeType): self
    {
        $this->fileMimeType = $fileMimeType;

        return $this;
    }

    public function getFileMimeType(): ?string
    {
        return $this->fileMimeType;
    }

    public function addUserFavorite(User $userFavorite): self
    {
        if (!$this->userFavorites->contains($userFavorite)) {
            $this->userFavorites[] = $userFavorite;
        }

        return $this;
    }

    public function removeUserFavorite(User $userFavorite): self
    {
        if ($this->userFavorites->contains($userFavorite)) {
            $this->userFavorites->removeElement($userFavorite);
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserFavorites(): Collection
    {
        return $this->userFavorites;
    }

    public function setLevel(?Level $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getLevel(): ?Level
    {
        return $this->level;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }
}
