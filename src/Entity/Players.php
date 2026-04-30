<?php

namespace App\Entity;

use App\Enum\PlayerCategorie;
use App\Enum\PlayerStatut;
use App\Repository\PlayersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayersRepository::class)]
class Players
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    private ?string $last_name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $birthdate = null;

    #[ORM\Column(enumType: PlayerStatut::class)]
    private ?PlayerStatut $statut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $licence = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $medical_certificate = null;

    #[ORM\Column(enumType: PlayerCategorie::class)]
    private ?PlayerCategorie $categorie = null;

    #[ORM\Column(nullable: true)]
    private ?int $number = null;

    #[ORM\ManyToOne(inversedBy: 'players')]
    private ?Teams $Teams = null;

    /**
     * @var Collection<int, Ratings>
     */
    #[ORM\ManyToMany(targetEntity: Ratings::class, inversedBy: 'players')]
    private Collection $Ratings;

    /**
     * @var Collection<int, Abscences>
     */
    #[ORM\OneToMany(targetEntity: Abscences::class, mappedBy: 'players')]
    private Collection $relation;

    /**
     * @var Collection<int, Blames>
     */
    #[ORM\OneToMany(targetEntity: Blames::class, mappedBy: 'players')]
    private Collection $Blames;

    public function __construct()
    {
        $this->Ratings = new ArrayCollection();
        $this->relation = new ArrayCollection();
        $this->Blames = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getBirthdate(): ?\DateTime
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTime $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * @return PlayerStatut[]
     */
    public function getStatut()
    {
        return $this->statut;
    }

    public function setStatut($statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getLicence(): ?\DateTime
    {
        return $this->licence;
    }

    public function setLicence(\DateTime $licence): static
    {
        $this->licence = $licence;

        return $this;
    }

    public function getMedicalCertificate(): ?\DateTime
    {
        return $this->medical_certificate;
    }

    public function setMedicalCertificate(\DateTime $medical_certificate): static
    {
        $this->medical_certificate = $medical_certificate;

        return $this;
    }

    public function getCategorie(): ?PlayerCategorie
    {
        return $this->categorie;
    }

    public function setCategorie(PlayerCategorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getTeams(): ?Teams
    {
        return $this->Teams;
    }

    public function setTeams(?Teams $Teams): static
    {
        $this->Teams = $Teams;

        return $this;
    }

    /**
     * @return Collection<int, Ratings>
     */
    public function getRatings(): Collection
    {
        return $this->Ratings;
    }

    public function addRating(Ratings $rating): static
    {
        if (!$this->Ratings->contains($rating)) {
            $this->Ratings->add($rating);
        }

        return $this;
    }

    public function removeRating(Ratings $rating): static
    {
        $this->Ratings->removeElement($rating);

        return $this;
    }

    /**
     * @return Collection<int, Abscences>
     */
    public function getRelation(): Collection
    {
        return $this->relation;
    }

    public function addRelation(Abscences $relation): static
    {
        if (!$this->relation->contains($relation)) {
            $this->relation->add($relation);
            $relation->setPlayers($this);
        }

        return $this;
    }

    public function removeRelation(Abscences $relation): static
    {
        if ($this->relation->removeElement($relation)) {
            // set the owning side to null (unless already changed)
            if ($relation->getPlayers() === $this) {
                $relation->setPlayers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Blames>
     */
    public function getBlames(): Collection
    {
        return $this->Blames;
    }

    public function addBlame(Blames $blame): static
    {
        if (!$this->Blames->contains($blame)) {
            $this->Blames->add($blame);
            $blame->setPlayers($this);
        }

        return $this;
    }

    public function removeBlame(Blames $blame): static
    {
        if ($this->Blames->removeElement($blame)) {
            // set the owning side to null (unless already changed)
            if ($blame->getPlayers() === $this) {
                $blame->setPlayers(null);
            }
        }

        return $this;
    }

}
