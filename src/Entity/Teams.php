<?php

namespace App\Entity;

use App\Repository\TeamsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamsRepository::class)]
class Teams
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $team_name = null;

    /**
     * @var Collection<int, Players>
     */
    #[ORM\OneToMany(targetEntity: Players::class, mappedBy: 'Teams')]
    private Collection $players;

    #[ORM\ManyToOne(inversedBy: 'Teams')]
    private ?User $user = null;

    /**
     * Stocks / inventaires associés à cette équipe (plusieurs lignes possibles).
     *
     * @var Collection<int, Inventory>
     */
    #[ORM\OneToMany(targetEntity: Inventory::class, mappedBy: 'team', orphanRemoval: false)]
    private Collection $inventories;

    /**
     * @var Collection<int, Matches>
     */
    #[ORM\OneToMany(targetEntity: Matches::class, mappedBy: 'homeTeam')]
    private Collection $homeMatches;

    /**
     * @var Collection<int, Matches>
     */
    #[ORM\OneToMany(targetEntity: Matches::class, mappedBy: 'awayTeam')]
    private Collection $awayMatches;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->homeMatches = new ArrayCollection();
        $this->awayMatches = new ArrayCollection();
        $this->inventories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeamName(): ?string
    {
        return $this->team_name;
    }

    public function setTeamName(string $team_name): static
    {
        $this->team_name = $team_name;

        return $this;
    }

    /**
     * @return Collection<int, Players>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Players $player): static
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
            $player->setTeams($this);
        }

        return $this;
    }

    public function removePlayer(Players $player): static
    {
        if ($this->players->removeElement($player)) {
            if ($player->getTeams() === $this) {
                $player->setTeams(null);
            }
        }

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

    /**
     * @return Collection<int, Inventory>
     */
    public function getInventories(): Collection
    {
        return $this->inventories;
    }

    public function addInventory(Inventory $inventory): static
    {
        if (!$this->inventories->contains($inventory)) {
            $this->inventories->add($inventory);
            $inventory->setTeam($this);
        }

        return $this;
    }

    public function removeInventory(Inventory $inventory): static
    {
        if ($this->inventories->removeElement($inventory) && $inventory->getTeam() === $this) {
            $inventory->setTeam(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Matches>
     */
    public function getHomeMatches(): Collection
    {
        return $this->homeMatches;
    }

    /**
     * @return Collection<int, Matches>
     */
    public function getAwayMatches(): Collection
    {
        return $this->awayMatches;
    }
}
