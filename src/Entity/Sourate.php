<?php

namespace App\Entity;

use App\Repository\SourateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SourateRepository::class)]
class Sourate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'verset', targetEntity: Verset::class)]
    private Collection $versets;

    public function __construct()
    {
        $this->versets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, Verset>
     */
    public function getVersets(): Collection
    {
        return $this->versets;
    }

    public function addVerset(Verset $verset): static
    {
        if (!$this->versets->contains($verset)) {
            $this->versets->add($verset);
            $verset->setVerset($this);
        }

        return $this;
    }

    public function removeVerset(Verset $verset): static
    {
        if ($this->versets->removeElement($verset)) {
            // set the owning side to null (unless already changed)
            if ($verset->getVerset() === $this) {
                $verset->setVerset(null);
            }
        }

        return $this;
    }
}
