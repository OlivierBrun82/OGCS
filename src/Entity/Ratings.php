<?php

namespace App\Entity;

use App\Repository\RatingsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RatingsRepository::class)]
#[ORM\Table(uniqueConstraints: [new ORM\UniqueConstraint(name: 'uniq_rating_coach_player', columns: ['coach_id', 'player_id'])])]
#[ORM\HasLifecycleCallbacks]
class Ratings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'coachRatings')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $coach = null;

    #[ORM\ManyToOne(inversedBy: 'playerRatings')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Players $player = null;

    #[Assert\NotNull(message: 'La note est obligatoire.')]
    #[Assert\Range(min: 1, max: 10, notInRangeMessage: 'La note doit être comprise entre {{ min }} et {{ max }}.')]
    #[ORM\Column]
    private ?int $rating = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $message = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        if ($this->created_at === null) {
            $this->created_at = new \DateTimeImmutable();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoach(): ?User
    {
        return $this->coach;
    }

    public function setCoach(?User $coach): static
    {
        $this->coach = $coach;

        return $this;
    }

    public function getPlayer(): ?Players
    {
        return $this->player;
    }

    public function setPlayer(?Players $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
