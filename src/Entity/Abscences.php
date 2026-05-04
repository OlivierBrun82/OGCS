<?php

namespace App\Entity;

use App\Repository\AbscencesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: AbscencesRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Assert\Callback('validateDates')]
class Abscences
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Indiquez le motif de l\'absence.')]
    #[ORM\Column(length: 255)]
    private ?string $motive = null;

    #[Assert\NotBlank(message: 'Indiquez la date de début.')]
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $absence_start = null;

    #[Assert\NotBlank(message: 'Indiquez la date de fin.')]
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $absence_end = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[Assert\NotNull(message: 'Choisissez un joueur.')]
    #[ORM\ManyToOne(inversedBy: 'relation')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Players $players = null;

    #[ORM\ManyToOne(inversedBy: 'Abscences')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\PrePersist]
    public function setTimestampsOnCreate(): void
    {
        $now = new \DateTimeImmutable();
        if ($this->created_at === null) {
            $this->created_at = $now;
        }
        $this->updated_at = $now;
    }

    #[ORM\PreUpdate]
    public function bumpUpdatedAt(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }

    public function validateDates(ExecutionContextInterface $context): void
    {
        if ($this->absence_start !== null && $this->absence_end !== null
            && $this->absence_end < $this->absence_start) {
            $context->buildViolation('La date de fin doit être postérieure ou égale à la date de début.')
                ->atPath('absence_end')
                ->addViolation();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMotive(): ?string
    {
        return $this->motive;
    }

    public function setMotive(string $motive): static
    {
        $this->motive = $motive;

        return $this;
    }

    public function getAbsenceStart(): ?\DateTimeImmutable
    {
        return $this->absence_start;
    }

    public function setAbsenceStart(?\DateTimeImmutable $absence_start): static
    {
        $this->absence_start = $absence_start;

        return $this;
    }

    public function getAbsenceEnd(): ?\DateTimeImmutable
    {
        return $this->absence_end;
    }

    public function setAbsenceEnd(?\DateTimeImmutable $absence_end): static
    {
        $this->absence_end = $absence_end;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

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
}
