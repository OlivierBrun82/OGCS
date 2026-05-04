<?php

namespace App\Entity;

use App\Enum\BlameCardType;
use App\Repository\BlamesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: BlamesRepository::class)]
class Blames
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull(message: 'La date de début est obligatoire.')]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $start_date = null;

    #[Assert\NotNull(message: 'Le type de carton est obligatoire.')]
    #[ORM\Column(enumType: BlameCardType::class)]
    private ?BlameCardType $card_type = null;

    /** Durée d'exclusion en minutes (carton blanc uniquement). */
    #[ORM\Column(nullable: true)]
    private ?int $duration_minutes = null;

    /** Nombre de matchs de suspension (cartons jaune et rouge). */
    #[ORM\Column(nullable: true)]
    private ?int $suspension_matches = null;

    #[ORM\ManyToOne(inversedBy: 'Blames')]
    private ?Players $players = null;

    #[ORM\ManyToOne(inversedBy: 'Blames')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'blames')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Matches $related_match = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->start_date;
    }

    public function setStartDate(?\DateTime $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getCardType(): ?BlameCardType
    {
        return $this->card_type;
    }

    public function setCardType(?BlameCardType $card_type): static
    {
        $this->card_type = $card_type;

        return $this;
    }

    public function getDurationMinutes(): ?int
    {
        return $this->duration_minutes;
    }

    public function setDurationMinutes(?int $duration_minutes): static
    {
        $this->duration_minutes = $duration_minutes;

        return $this;
    }

    public function getSuspensionMatches(): ?int
    {
        return $this->suspension_matches;
    }

    public function setSuspensionMatches(?int $suspension_matches): static
    {
        $this->suspension_matches = $suspension_matches;

        return $this;
    }

    public function getPlayers(): ?Players
    {
        return $this->players;
    }

    public function setPlayers(?Players $players): static
    {
        $this->players = $players;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getRelatedMatch(): ?Matches
    {
        return $this->related_match;
    }

    public function setRelatedMatch(?Matches $related_match): static
    {
        $this->related_match = $related_match;

        return $this;
    }

    #[Assert\Callback]
    public function validateSanctionFields(ExecutionContextInterface $context): void
    {
        if ($this->card_type === BlameCardType::White) {
            if ($this->duration_minutes === null || $this->duration_minutes < 1) {
                $context->buildViolation('Indiquez une durée en minutes (au moins 1) pour un carton blanc.')
                    ->atPath('duration_minutes')
                    ->addViolation();
            }
        }

        if ($this->card_type === BlameCardType::Yellow || $this->card_type === BlameCardType::Red) {
            if ($this->suspension_matches !== null && $this->suspension_matches < 0) {
                $context->buildViolation('Le nombre de matchs de suspension ne peut pas être négatif.')
                    ->atPath('suspension_matches')
                    ->addViolation();
            }
        }
    }
}
