<?php

namespace App\Entity;

use App\Repository\NotesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotesRepository::class)]
class Notes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    private ?Jury $jury = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    private ?Question $Questions = null;

    #[ORM\Column(nullable: true)]
    private ?int $memorisation = null;

    #[ORM\Column(nullable: true)]
    private ?int $tajwid = null;

    #[ORM\Column(nullable: true)]
    private ?int $lecture = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaires = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJury(): ?Jury
    {
        return $this->jury;
    }

    public function setJury(?Jury $jury): static
    {
        $this->jury = $jury;

        return $this;
    }

    public function getQuestions(): ?Question
    {
        return $this->Questions;
    }

    public function setQuestions(?Question $Questions): static
    {
        $this->Questions = $Questions;

        return $this;
    }

    public function getMemorisation(): ?int
    {
        return $this->memorisation;
    }

    public function setMemorisation(?int $memorisation): static
    {
        $this->memorisation = $memorisation;

        return $this;
    }

    public function getTajwid(): ?int
    {
        return $this->tajwid;
    }

    public function setTajwid(?int $tajwid): static
    {
        $this->tajwid = $tajwid;

        return $this;
    }

    public function getLecture(): ?int
    {
        return $this->lecture;
    }

    public function setLecture(?int $lecture): static
    {
        $this->lecture = $lecture;

        return $this;
    }

    public function getCommentaires(): ?string
    {
        return $this->commentaires;
    }

    public function setCommentaires(?string $commentaires): static
    {
        $this->commentaires = $commentaires;

        return $this;
    }
}
