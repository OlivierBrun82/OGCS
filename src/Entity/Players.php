<?php

namespace App\Entity;

use App\Enum\PlayerCategorie;
use App\Enum\PlayerStatut;
use App\Repository\PlayersRepository;
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

    #[ORM\Column(type: Types::SIMPLE_ARRAY, enumType: PlayerStatut::class)]
    private array $statut = [];

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $licence = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $medical_certificate = null;

    #[ORM\Column(enumType: PlayerCategorie::class)]
    private ?PlayerCategorie $catagorie = null;

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
    public function getStatut(): array
    {
        return $this->statut;
    }

    public function setStatut(array $statut): static
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

    public function getCatagorie(): ?PlayerCategorie
    {
        return $this->catagorie;
    }

    public function setCatagorie(PlayerCategorie $catagorie): static
    {
        $this->catagorie = $catagorie;

        return $this;
    }
}
