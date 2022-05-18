<?php

namespace App\Entity;

use App\Repository\TerrainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TerrainRepository::class)
 */
class Terrain
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $tarif;

    /**
     * @ORM\Column(type="float")
     */
    private $unite;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Complex::class, inversedBy="terrains")
     * @ORM\JoinColumn(nullable=false)
     */
    private $complex;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="terrain", orphanRemoval=true)
     */
    private $reservations;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $jour_dispo = [];

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $heure_deb;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $heure_fin;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTarif(): ?float
    {
        return $this->tarif;
    }

    public function setTarif(float $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getUnite(): ?float
    {
        return $this->unite;
    }

    public function setUnite(float $unite): self
    {
        $this->unite = $unite;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getComplex(): ?complex
    {
        return $this->complex;
    }

    public function setComplex(?complex $complex): self
    {
        $this->complex = $complex;

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
            $reservation->setTerrain($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getTerrain() === $this) {
                $reservation->setTerrain(null);
            }
        }

        return $this;
    }

    public function getJourDispo(): ?array
    {
        return $this->jour_dispo;
    }

    public function setJourDispo(?array $jour_dispo): self
    {
        $this->jour_dispo = $jour_dispo;

        return $this;
    }

    public function getHeureDeb(): ?\DateTimeInterface
    {
        return $this->heure_deb;
    }

    public function setHeureDeb(?\DateTimeInterface $heure_deb): self
    {
        $this->heure_deb = $heure_deb;

        return $this;
    }

    public function getHeureFin(): ?\DateTimeInterface
    {
        return $this->heure_fin;
    }

    public function setHeureFin(?\DateTimeInterface $heure_fin): self
    {
        $this->heure_fin = $heure_fin;

        return $this;
    }
}
