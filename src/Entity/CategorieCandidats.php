<?php

namespace App\Entity;

use App\Repository\CategorieCandidatsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieCandidatsRepository::class)]
class CategorieCandidats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Candidat::class)]
    private Collection $candidats;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $debutSourate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $finSourate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    public function __construct()
    {
        $this->candidats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection<int, Candidat>
     */
    public function getCandidats(): Collection
    {
        return $this->candidats;
    }

    public function addCandidat(Candidat $candidat): self
    {
        if (!$this->candidats->contains($candidat)) {
            $this->candidats->add($candidat);
            $candidat->setCategorie($this);
        }

        return $this;
    }

    public function removeCandidat(Candidat $candidat): self
    {
        if ($this->candidats->removeElement($candidat)) {
            // set the owning side to null (unless already changed)
            if ($candidat->getCategorie() === $this) {
                $candidat->setCategorie(null);
            }
        }

        return $this;
    }

    public function getDebutSourate(): ?string
    {
        return $this->debutSourate;
    }

    public function setDebutSourate(string $debutSourate): self
    {
        $this->debutSourate = $debutSourate;

        return $this;
    }

    public function getFinSourate(): ?string
    {
        return $this->finSourate;
    }

    public function setFinSourate(?string $finSourate): self
    {
        $this->finSourate = $finSourate;

        return $this;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getName();
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
