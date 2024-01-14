<?php

namespace App\Entity;

use App\Repository\JuryRepository;
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
}
