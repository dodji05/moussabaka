<?php

namespace App\Entity;

use App\Repository\AnneeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnneeRepository::class)]
class Annee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $annee = null;

    #[ORM\OneToMany(mappedBy: 'annnee', targetEntity: Jury::class)]
    private Collection $juries;

    public function __construct()
    {
        $this->juries = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnee(): ?string
    {
        return $this->annee;
    }

    public function setAnnee(?string $annee): static
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * @return Collection<int, Jury>
     */
    public function getJuries(): Collection
    {
        return $this->juries;
    }

    public function addJury(Jury $jury): static
    {
        if (!$this->juries->contains($jury)) {
            $this->juries->add($jury);
            $jury->setAnnnee($this);
        }

        return $this;
    }

    public function removeJury(Jury $jury): static
    {
        if ($this->juries->removeElement($jury)) {
            // set the owning side to null (unless already changed)
            if ($jury->getAnnnee() === $this) {
                $jury->setAnnnee(null);
            }
        }

        return $this;
    }


}
