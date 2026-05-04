<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Il y a déjà un compte avec cette email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * Notes données par ce coach (utilisateur).
     *
     * @var Collection<int, Ratings>
     */
    #[ORM\OneToMany(targetEntity: Ratings::class, mappedBy: 'coach', orphanRemoval: false)]
    private Collection $coachRatings;

    /**
     * @var Collection<int, Teams>
     */
    #[ORM\OneToMany(targetEntity: Teams::class, mappedBy: 'user')]
    private Collection $Teams;

    /**
     * @var Collection<int, Abscences>
     */
    #[ORM\OneToMany(targetEntity: Abscences::class, mappedBy: 'user')]
    private Collection $Abscences;

    /**
     * @var Collection<int, Blames>
     */
    #[ORM\OneToMany(targetEntity: Blames::class, mappedBy: 'user')]
    private Collection $Blames;

    /**
     * @var Collection<int, Mailling>
     */
    #[ORM\ManyToMany(targetEntity: Mailling::class, inversedBy: 'users')]
    private Collection $Mailling;

    /**
     * Messages envoyés.
     *
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'sender', orphanRemoval: false)]
    private Collection $sentMessages;

    /**
     * Messages reçus.
     *
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'recipient', orphanRemoval: false)]
    private Collection $receivedMessages;

    public function __construct()
    {
        $this->coachRatings = new ArrayCollection();
        $this->Teams = new ArrayCollection();
        $this->Abscences = new ArrayCollection();
        $this->Blames = new ArrayCollection();
        $this->Mailling = new ArrayCollection();
        $this->sentMessages = new ArrayCollection();
        $this->receivedMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    /**
     * @return Collection<int, Ratings>
     */
    public function getCoachRatings(): Collection
    {
        return $this->coachRatings;
    }

    public function addCoachRating(Ratings $rating): static
    {
        if (!$this->coachRatings->contains($rating)) {
            $this->coachRatings->add($rating);
            $rating->setCoach($this);
        }

        return $this;
    }

    public function removeCoachRating(Ratings $rating): static
    {
        $this->coachRatings->removeElement($rating);

        return $this;
    }

    /**
     * @return Collection<int, Teams>
     */
    public function getTeams(): Collection
    {
        return $this->Teams;
    }

    public function addTeam(Teams $team): static
    {
        if (!$this->Teams->contains($team)) {
            $this->Teams->add($team);
            $team->setUser($this);
        }

        return $this;
    }

    public function removeTeam(Teams $team): static
    {
        if ($this->Teams->removeElement($team)) {
            // set the owning side to null (unless already changed)
            if ($team->getUser() === $this) {
                $team->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Abscences>
     */
    public function getAbscences(): Collection
    {
        return $this->Abscences;
    }

    public function addAbscence(Abscences $abscence): static
    {
        if (!$this->Abscences->contains($abscence)) {
            $this->Abscences->add($abscence);
            $abscence->setUser($this);
        }

        return $this;
    }

    public function removeAbscence(Abscences $abscence): static
    {
        if ($this->Abscences->removeElement($abscence)) {
            // set the owning side to null (unless already changed)
            if ($abscence->getUser() === $this) {
                $abscence->setUser(null);
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
            $blame->setUser($this);
        }

        return $this;
    }

    public function removeBlame(Blames $blame): static
    {
        if ($this->Blames->removeElement($blame)) {
            // set the owning side to null (unless already changed)
            if ($blame->getUser() === $this) {
                $blame->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Mailling>
     */
    public function getMailling(): Collection
    {
        return $this->Mailling;
    }

    public function addMailling(Mailling $mailling): static
    {
        if (!$this->Mailling->contains($mailling)) {
            $this->Mailling->add($mailling);
        }

        return $this;
    }

    public function removeMailling(Mailling $mailling): static
    {
        $this->Mailling->removeElement($mailling);

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getSentMessages(): Collection
    {
        return $this->sentMessages;
    }

    public function addSentMessage(Message $message): static
    {
        if (!$this->sentMessages->contains($message)) {
            $this->sentMessages->add($message);
            $message->setSender($this);
        }

        return $this;
    }

    public function removeSentMessage(Message $message): static
    {
        $this->sentMessages->removeElement($message);

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getReceivedMessages(): Collection
    {
        return $this->receivedMessages;
    }

    public function addReceivedMessage(Message $message): static
    {
        if (!$this->receivedMessages->contains($message)) {
            $this->receivedMessages->add($message);
            $message->setRecipient($this);
        }

        return $this;
    }

    public function removeReceivedMessage(Message $message): static
    {
        $this->receivedMessages->removeElement($message);

        return $this;
    }
}
