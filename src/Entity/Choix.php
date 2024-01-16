<?php

namespace App\Entity;

use App\Repository\ChoixRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChoixRepository::class)]
class Choix
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $categorie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $candidat = null;

    #[ORM\Column(nullable: true)]
    private ?int $choiceIndex = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getCandidat(): ?string
    {
        return $this->candidat;
    }

    public function setCandidat(?string $candidat): static
    {
        $this->candidat = $candidat;

        return $this;
    }

    public function getChoiceIndex(): ?int
    {
        return $this->choiceIndex;
    }

    public function setChoiceIndex(?int $choiceIndex): static
    {
        $this->choiceIndex = $choiceIndex;

        return $this;
    }
}
