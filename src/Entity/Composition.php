<?php

namespace App\Entity;

use App\Enum\MatchPlayerRole;
use App\Repository\CompositionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: CompositionRepository::class)]
#[ORM\Table(name: 'match_composition')]
#[ORM\UniqueConstraint(name: 'uniq_composition_match_player', columns: ['match_id', 'player_id'])]
#[UniqueEntity(fields: ['match', 'player'], message: 'Ce joueur figure déjà dans la composition du match.')]
#[Assert\Callback('validatePlayerTeam')]
class Composition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'compositions')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Matches $match = null;

    #[Assert\NotNull(message: 'Sélectionnez un joueur.')]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Players $player = null;

    #[Assert\NotNull(message: 'Choisissez un rôle pour ce match.')]
    #[ORM\Column(enumType: MatchPlayerRole::class)]
    private ?MatchPlayerRole $role = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatch(): ?Matches
    {
        return $this->match;
    }

    public function setMatch(?Matches $match): static
    {
        $this->match = $match;

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

    public function getRole(): ?MatchPlayerRole
    {
        return $this->role;
    }

    public function setRole(?MatchPlayerRole $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function validatePlayerTeam(ExecutionContextInterface $context): void
    {
        if ($this->match === null || $this->player === null || $this->player->getTeams() === null) {
            return;
        }

        $playerTeam = $this->player->getTeams();
        $home = $this->match->getHomeTeam();
        $away = $this->match->getAwayTeam();

        if ($home === null || $away === null) {
            return;
        }

        if ($playerTeam->getId() !== null && $home->getId() !== null && $away->getId() !== null) {
            if ($playerTeam->getId() !== $home->getId() && $playerTeam->getId() !== $away->getId()) {
                $context->buildViolation('Ce joueur ne fait ni partie de l\'équipe domicile ni de l\'équipe extérieure.')
                    ->atPath('player')
                    ->addViolation();
            }

            return;
        }

        if ($playerTeam !== $home && $playerTeam !== $away) {
            $context->buildViolation('Ce joueur ne fait ni partie de l\'équipe domicile ni de l\'équipe extérieure.')
                ->atPath('player')
                ->addViolation();
        }
    }
}
