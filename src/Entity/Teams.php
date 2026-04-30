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

    #[ORM\ManyToOne(inversedBy: 'teams')]
    private ?Inventory $Inventory = null;

    /**
     * @var Collection<int, Matches>
     */
    #[ORM\ManyToMany(targetEntity: Matches::class, inversedBy: 'teams')]
    private Collection $Matches;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->Matches = new ArrayCollection();
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
            // set the owning side to null (unless already changed)
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

    public function getInventory(): ?Inventory
    {
        return $this->Inventory;
    }

    public function setInventory(?Inventory $Inventory): static
    {
        $this->Inventory = $Inventory;

        return $this;
    }

    /**
     * @return Collection<int, Matches>
     */
    public function getMatches(): Collection
    {
        return $this->Matches;
    }

    public function addMatch(Matches $match): static
    {
        if (!$this->Matches->contains($match)) {
            $this->Matches->add($match);
        }

        return $this;
    }

    public function removeMatch(Matches $match): static
    {
        $this->Matches->removeElement($match);

        return $this;
    }
}
