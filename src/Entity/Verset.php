<?php

namespace App\Entity;

use App\Repository\VersetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VersetRepository::class)]
class Verset
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\ManyToOne(inversedBy: 'versets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sourate $verset = null;

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

    public function getVerset(): ?Sourate
    {
        return $this->verset;
    }

    public function setVerset(?Sourate $verset): static
    {
        $this->verset = $verset;

        return $this;
    }
}
