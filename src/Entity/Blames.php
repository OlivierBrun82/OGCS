<?php

namespace App\Entity;

use App\Repository\BlamesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlamesRepository::class)]
class Blames
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $start_date = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $white_card = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $yellow_card = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $red_card = null;

    #[ORM\ManyToOne(inversedBy: 'Blames')]
    private ?Players $players = null;

    #[ORM\ManyToOne(inversedBy: 'Blames')]
    private ?User $user = null;

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

    public function getWhiteCard(): ?\DateTime
    {
        return $this->white_card;
    }

    public function setWhiteCard(?\DateTime $white_card): static
    {
        $this->white_card = $white_card;

        return $this;
    }

    public function getYellowCard(): ?\DateTime
    {
        return $this->yellow_card;
    }

    public function setYellowCard(?\DateTime $yellow_card): static
    {
        $this->yellow_card = $yellow_card;

        return $this;
    }

    public function getRedCard(): ?\DateTime
    {
        return $this->red_card;
    }

    public function setRedCard(?\DateTime $red_card): static
    {
        $this->red_card = $red_card;

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
