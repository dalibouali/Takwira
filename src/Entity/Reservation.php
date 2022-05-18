<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{


    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $maker;

    /**
     *
     * @ORM\Column(type="datetime")
     */
    private $date;



    /**
     * @ORM\Column(type="integer")
     */
    private $etat;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Terrain::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $terrain;

    /**
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaker(): ?user
    {
        return $this->maker;
    }

    public function setMaker(?user $maker): self
    {
        $this->maker = $maker;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }



    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getTerrain(): ?terrain
    {
        return $this->terrain;
    }

    public function setTerrain(?terrain $terrain): self
    {
        $this->terrain = $terrain;

        return $this;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
