<?php

namespace App\Entity;

use App\Repository\EquipesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipesRepository::class)
 * @UniqueEntity(fields={"pseudo"}, message="There is already a team with this pseudo")
 */
class Equipes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $pseudo;

    /**
     * @ORM\ManyToOne(targetEntity=user::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $capitaine;

    /**
     * @ORM\ManyToMany(targetEntity=user::class, inversedBy="equipes")
     */
    private $membres;

    public function __construct()
    {
        $this->membres = new ArrayCollection();
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

    public function getCapitaine(): ?user
    {
        return $this->capitaine;
    }

    public function setCapitaine(?user $capitaine): self
    {
        $this->capitaine = $capitaine;

        return $this;
    }

    /**
     * @return Collection|user[]
     */
    public function getMembres(): Collection
    {
        return $this->membres;
    }

    public function addMembre(user $membre): self
    {
        if (!$this->membres->contains($membre)) {
            $this->membres[] = $membre;
        }

        return $this;
    }

    public function removeMembre(user $membre): self
    {
        $this->membres->removeElement($membre);

        return $this;
    }
}
