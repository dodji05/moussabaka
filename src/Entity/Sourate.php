<?php

namespace App\Entity;

use App\Repository\SourateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SourateRepository::class)]
class Sourate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column]
    private ?int $surahnumber = null;

    #[ORM\Column(length: 255)]
    private ?string $surahnamearabic = null;

    #[ORM\Column(length: 255)]
    private ?string $surahnameenglish = null;

    #[ORM\Column]
    private ?int $ayahnumber = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $originalarabictext = null;

    #[ORM\Column]
    private ?bool $isReaded = null;




    public function getId(): ?int
    {
        return $this->id;
    }



    public function getSurahnumber(): ?int
    {
        return $this->surahnumber;
    }

    public function setSurahnumber(int $surahnumber): static
    {
        $this->surahnumber = $surahnumber;

        return $this;
    }

    public function getSurahnamearabic(): ?string
    {
        return $this->surahnamearabic;
    }

    public function setSurahnamearabic(string $surahnamearabic): static
    {
        $this->surahnamearabic = $surahnamearabic;

        return $this;
    }

    public function getSurahnameenglish(): ?string
    {
        return $this->surahnameenglish;
    }

    public function setSurahnameenglish(string $surahnameenglish): static
    {
        $this->surahnameenglish = $surahnameenglish;

        return $this;
    }

    public function getAyahnumber(): ?int
    {
        return $this->ayahnumber;
    }

    public function setAyahnumber(int $ayahnumber): static
    {
        $this->ayahnumber = $ayahnumber;

        return $this;
    }

    public function getOriginalarabictext(): ?string
    {
        return $this->originalarabictext;
    }

    public function setOriginalarabictext(string $originalarabictext): static
    {
        $this->originalarabictext = $originalarabictext;

        return $this;
    }

    public function isIsReaded(): ?bool
    {
        return $this->isReaded;
    }

    public function setIsReaded(bool $isReaded): static
    {
        $this->isReaded = $isReaded;

        return $this;
    }






}
