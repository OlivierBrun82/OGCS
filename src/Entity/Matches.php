<?php

namespace App\Entity;

use App\Repository\MatchesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: MatchesRepository::class)]
#[Assert\Callback('validateTeams')]
#[Assert\Callback('validateCompositionPlayerUniqueness')]
#[Assert\Callback('validateScores')]
class Matches
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[Assert\NotNull(message: 'Choisissez l\'équipe à domicile.')]
    #[ORM\ManyToOne(inversedBy: 'homeMatches')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private ?Teams $homeTeam = null;

    #[Assert\NotNull(message: 'Choisissez l\'équipe à l\'extérieur.')]
    #[ORM\ManyToOne(inversedBy: 'awayMatches')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private ?Teams $awayTeam = null;

    #[Assert\GreaterThanOrEqual(0)]
    #[ORM\Column(nullable: true)]
    private ?int $homeScore = null;

    #[Assert\GreaterThanOrEqual(0)]
    #[ORM\Column(nullable: true)]
    private ?int $awayScore = null;

    /**
     * @var Collection<int, Blames>
     */
    #[ORM\OneToMany(targetEntity: Blames::class, mappedBy: 'related_match')]
    private Collection $blames;

    /**
     * Feuille de match : joueur + rôle pour cette rencontre.
     *
     * @var Collection<int, Composition>
     */
    #[ORM\OneToMany(targetEntity: Composition::class, mappedBy: 'match', cascade: ['persist'], orphanRemoval: true)]
    #[ORM\OrderBy(['id' => 'ASC'])]
    private Collection $compositions;

    public function __construct()
    {
        $this->blames = new ArrayCollection();
        $this->compositions = new ArrayCollection();
    }

    public function validateTeams(ExecutionContextInterface $context): void
    {
        if ($this->homeTeam === null || $this->awayTeam === null) {
            return;
        }

        $sameReference = $this->homeTeam === $this->awayTeam;
        $sameId = $this->homeTeam->getId() !== null
            && $this->awayTeam->getId() !== null
            && $this->homeTeam->getId() === $this->awayTeam->getId();

        if ($sameReference || $sameId) {
            $context->buildViolation('L\'équipe domicile et l\'équipe extérieure doivent être distinctes.')
                ->atPath('awayTeam')
                ->addViolation();
        }
    }

    public function validateCompositionPlayerUniqueness(ExecutionContextInterface $context): void
    {
        $seen = [];
        foreach ($this->compositions as $line) {
            $player = $line->getPlayer();
            if ($player === null) {
                continue;
            }
            foreach ($seen as $existing) {
                if ($existing === $player) {
                    $context->buildViolation('Chaque joueur ne peut être présent qu\'une fois dans la composition.')
                        ->atPath('compositions')
                        ->addViolation();

                    return;
                }
            }
            $seen[] = $player;
        }
    }

    public function validateScores(ExecutionContextInterface $context): void
    {
        $home = $this->homeScore;
        $away = $this->awayScore;
        if ($home !== null && $away === null) {
            $context->buildViolation('Renseignez aussi le score de l\'équipe à l\'extérieur (ou videz le score domicile).')
                ->atPath('awayScore')
                ->addViolation();
        }
        if ($away !== null && $home === null) {
            $context->buildViolation('Renseignez aussi le score de l\'équipe à domicile (ou videz le score extérieur).')
                ->atPath('homeScore')
                ->addViolation();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getHomeTeam(): ?Teams
    {
        return $this->homeTeam;
    }

    public function setHomeTeam(?Teams $homeTeam): static
    {
        $this->homeTeam = $homeTeam;

        return $this;
    }

    public function getAwayTeam(): ?Teams
    {
        return $this->awayTeam;
    }

    public function setAwayTeam(?Teams $awayTeam): static
    {
        $this->awayTeam = $awayTeam;

        return $this;
    }

    public function getHomeScore(): ?int
    {
        return $this->homeScore;
    }

    public function setHomeScore(?int $homeScore): static
    {
        $this->homeScore = $homeScore;

        return $this;
    }

    public function getAwayScore(): ?int
    {
        return $this->awayScore;
    }

    public function setAwayScore(?int $awayScore): static
    {
        $this->awayScore = $awayScore;

        return $this;
    }

    /**
     * @return Collection<int, Blames>
     */
    public function getBlames(): Collection
    {
        return $this->blames;
    }

    public function addBlame(Blames $blame): static
    {
        if (!$this->blames->contains($blame)) {
            $this->blames->add($blame);
            $blame->setRelatedMatch($this);
        }

        return $this;
    }

    public function removeBlame(Blames $blame): static
    {
        if ($this->blames->removeElement($blame)) {
            if ($blame->getRelatedMatch() === $this) {
                $blame->setRelatedMatch(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Composition>
     */
    public function getCompositions(): Collection
    {
        return $this->compositions;
    }

    public function addComposition(Composition $composition): static
    {
        if (!$this->compositions->contains($composition)) {
            $this->compositions->add($composition);
            $composition->setMatch($this);
        }

        return $this;
    }

    public function removeComposition(Composition $composition): static
    {
        $this->compositions->removeElement($composition);

        return $this;
    }

    public function setCompositions(iterable $compositions): void
    {
        foreach ($this->compositions->toArray() as $c) {
            $this->removeComposition($c);
        }
        foreach ($compositions as $c) {
            $this->addComposition($c);
        }
    }
}
