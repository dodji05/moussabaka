<?php

namespace App\Entity;

use App\Repository\JuryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JuryRepository::class)]
class Jury
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'juries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $membres = null;

    #[ORM\ManyToOne(inversedBy: 'juries')]
    private ?Annee $annnee = null;

    #[ORM\OneToMany(mappedBy: 'jury', targetEntity: Notes::class)]
    private Collection $notes;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMembres(): ?User
    {
        return $this->membres;
    }

    public function setMembres(?User $membres): static
    {
        $this->membres = $membres;

        return $this;
    }

    public function getAnnnee(): ?Annee
    {
        return $this->annnee;
    }

    public function setAnnnee(?Annee $annnee): static
    {
        $this->annnee = $annnee;

        return $this;
    }

    /**
     * @return Collection<int, Notes>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Notes $note): static
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setJury($this);
        }

        return $this;
    }

    public function removeNote(Notes $note): static
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getJury() === $this) {
                $note->setJury(null);
            }
        }

        return $this;
    }
}
