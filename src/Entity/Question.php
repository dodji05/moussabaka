<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    private ?Candidat $candidat = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    private ?Sourate $Sourate = null;

    #[ORM\OneToMany(mappedBy: 'Questions', targetEntity: Notes::class)]
    private Collection $notes;

    #[ORM\Column(nullable: true)]
    private ?bool $note = null;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->note = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidat(): ?Candidat
    {
        return $this->candidat;
    }

    public function setCandidat(?Candidat $candidat): static
    {
        $this->candidat = $candidat;

        return $this;
    }

    public function getSourate(): ?Sourate
    {
        return $this->Sourate;
    }

    public function setSourate(?Sourate $Sourate): static
    {
        $this->Sourate = $Sourate;

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
            $note->setQuestions($this);
        }

        return $this;
    }

    public function removeNote(Notes $note): static
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getQuestions() === $this) {
                $note->setQuestions(null);
            }
        }

        return $this;
    }

    public function isNote(): ?bool
    {
        return $this->note;
    }

    public function setNote(?bool $note): static
    {
        $this->note = $note;

        return $this;
    }
}
