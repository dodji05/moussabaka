<?php

namespace App\Entity;

use App\Repository\CandidatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidatRepository::class)]
class Candidat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numeroCandidat = null;

    #[ORM\ManyToOne(inversedBy: 'candidats')]
    private ?CategorieCandidats $categorie = null;

    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $age = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $localite = null;



    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\ManyToOne(inversedBy: 'candidats')]
    private ?EcoledeProvenance $ecoledeProvenance = null;

    #[ORM\OneToMany(mappedBy: 'candidat', targetEntity: Question::class)]
    private Collection $questions;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMom(): ?string
    {
        return $this->mom;
    }

    public function setMom(?string $mom): self
    {
        $this->mom = $mom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNumeroCandidat(): ?string
    {
        return $this->numeroCandidat;
    }

    public function setNumeroCandidat(?string $numeroCandidat): self
    {
        $this->numeroCandidat = $numeroCandidat;

        return $this;
    }

    public function getCategorie(): ?CategorieCandidats
    {
        return $this->categorie;
    }

    public function setCategorie(?CategorieCandidats $categorie): self
    {
        $this->categorie = $categorie;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }





    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getLocalite(): ?string
    {
        return $this->localite;
    }

    public function setLocalite(?string $localite): self
    {
        $this->localite = $localite;

        return $this;
    }



    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getEcoledeProvenance(): ?EcoledeProvenance
    {
        return $this->ecoledeProvenance;
    }

    public function setEcoledeProvenance(?EcoledeProvenance $ecoledeProvenance): static
    {
        $this->ecoledeProvenance = $ecoledeProvenance;

        return $this;
    }
    public function getFullName() {
        return "{$this->prenom} {$this->mom}";
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setCandidat($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getCandidat() === $this) {
                $question->setCandidat(null);
            }
        }

        return $this;
    }
}
