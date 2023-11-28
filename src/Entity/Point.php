<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\UniqueConstraint(columns: ['course_id', 'latitude', 'longitude'])]
#[UniqueEntity(fields: ['course', 'latitude', 'longitude'])]
class Point
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Course::class)]
    #[ORM\JoinColumn(name: 'course_id', referencedColumnName: 'id', nullable: false)]
    private ?Course $course = null;

    #[ORM\Column(type: 'decimal', precision: 9, scale: 6)]
    #[Assert\NotBlank]
    private ?string $latitude = null;

    #[ORM\Column(type: 'decimal', precision: 9, scale: 6)]
    #[Assert\NotBlank]
    private ?string $longitude = null;

    #[ORM\Column(type: 'integer', length: 1000)]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(0)]
    #[Assert\Length(min: 0, max: 1000)]
    private ?int $order = null;

    /*
     * Getters / Setters (auto-generated)
     */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setOrder(?int $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }
}
