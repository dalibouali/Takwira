<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"pseudo"}, message="There is already an account with this pseudo")
 * @UniqueEntity(fields={"mail"}, message="There is already an account with this mail")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *  @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "Prenom doit avoir au Moin {{ limit }} charactér",
     *      maxMessage = "Prenom doit avoir au Moin {{ limit }} charactére")
     *
     */
    private $pseudo;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100)
     *  @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "nom doit avoir au Moin {{ limit }} charactér")
     *      maxMessage = "nom doit avoir au Moin {{ limit }} charactére")
     *
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(
     *
     *      min = 3,
     *      max = 50,
     *      minMessage = "Prenom doit avoir au Moin {{ limit }} charactér",
     *      maxMessage = "Prenom doit avoir au Moin {{ limit }} charactére")
     *
     */
    private $prenom;

    /**
     * @ORM\Column(type="integer", nullable=true)c
     * @Assert\Length(
     *
     *      min = 8,
     *      max = 8,
     *      minMessage = "telephone doit avoir au Moin {{ limit }} charactér",
     *      maxMessage = "telephone doit avoir au Moin {{ limit }} charactére")
     * @Assert\Type(
     *     type="integer",
     *     message=" {{ value }} n 'est pas valide ")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255,unique=true)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.")
     *
     */
    private $mail;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\ManyToMany(targetEntity=Equipes::class, mappedBy="membres")
     */
    private $equipes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imagefile;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="maker", orphanRemoval=true)
     */
    private $reservations;



    /**
     * @ORM\OneToMany(targetEntity=Messages::class, mappedBy="sender")
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $sent;

    /**
     * @ORM\OneToMany(targetEntity=Messages::class, mappedBy="recipient")
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $recevied;

    /**
     * @ORM\ManyToMany(targetEntity=Conversation::class, mappedBy="participants")
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $conversations;





    /**
     * @return Collection|Messages[]
     */
    public function getSent(): Collection
    {
        return $this->sent;
    }

    public function addSent(Messages $sent): self
    {
        if (!$this->sent->contains($sent)) {
            $this->sent[] = $sent;
            $sent->setSender($this);
        }

        return $this;
    }

    public function removeSent(Messages $sent): self
    {
        if ($this->sent->removeElement($sent)) {
            // set the owning side to null (unless already changed)
            if ($sent->getSender() === $this) {
                $sent->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Messages[]
     */
    public function getRecevied(): Collection
    {
        return $this->recevied;
    }

    public function addRecevied(Messages $recevied): self
    {
        if (!$this->recevied->contains($recevied)) {
            $this->recevied[] = $recevied;
            $recevied->setRecipient($this);
        }

        return $this;
    }

    public function removeRecevied(Messages $recevied): self
    {
        if ($this->recevied->removeElement($recevied)) {
            // set the owning side to null (unless already changed)
            if ($recevied->getRecipient() === $this) {
                $recevied->setRecipient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Conversation[]
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
            $conversation->addParticipant($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->removeElement($conversation)) {
            $conversation->removeParticipant($this);
        }

        return $this;
    }



    public function __construct()
    {
        $this->equipes = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->pseudo;
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

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(?int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection|Equipes[]
     */
    public function getEquipes(): Collection
    {
        return $this->equipes;
    }

    public function addEquipe(Equipes $equipe): self
    {
        if (!$this->equipes->contains($equipe)) {
            $this->equipes[] = $equipe;
            $equipe->addMembre($this);
        }

        return $this;
    }

    public function removeEquipe(Equipes $equipe): self
    {
        if ($this->equipes->removeElement($equipe)) {
            $equipe->removeMembre($this);
        }

        return $this;
    }

    public function getImagefile(): ?string
    {
        return $this->imagefile;
    }

    public function setImagefile(?string $imagefile): self
    {
        $this->imagefile = $imagefile;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setMaker($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getMaker() === $this) {
                $reservation->setMaker(null);
            }
        }

        return $this;
    }

    public function getComplex(): ?Complex
    {
        return $this->complex;
    }

    public function setComplex(Complex $complex): self
    {
        // set the owning side of the relation if necessary
        if ($complex->getOwner() !== $this) {
            $complex->setOwner($this);
        }

        $this->complex = $complex;

        return $this;
    }
}
