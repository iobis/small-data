<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpeciesRepository")
 * @UniqueEntity(
 *     fields={"wormsAphiaId"},
 *     message = "Species identified by WoRMS AphiaId is already taken")
 * )
 */
class Species
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", unique=true)
     */
    private $wormsAphiaId;

    /**
     * @ORM\Column(type="text")
     */
    private $speciesNameWorms;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Occurrence", mappedBy="species")
     */
    private $occurrences;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Phylum", inversedBy="species")
     */
    private $phylum;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SpeciesImage", mappedBy="species")
     */
    private $speciesImages;








    public function __construct()
    {
        $this->occurrences = new ArrayCollection();
        $this->speciesImages = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getWormsAphiaId(): ?string
    {
        return $this->wormsAphiaId;
    }

    public function setWormsAphiaId(string $wormsAphiaId): self
    {
        $this->wormsAphiaId = $wormsAphiaId;

        return $this;
    }

    public function getSpeciesNameWorms(): ?string
    {
        return $this->speciesNameWorms;
    }

    public function setSpeciesNameWorms(string $speciesNameWorms): self
    {
        $this->speciesNameWorms = $speciesNameWorms;

        return $this;
    }

    public function getPhylum(): ?Phylum
    {
        return $this->phylum;
    }

    public function setPhylum(?Phylum $phylum): self
    {
        $this->phylum = $phylum;

        return $this;
    }





    /**
     * @return Collection|Occurrence[]
     */
    public function getOccurrences(): Collection
    {
        return $this->occurrences;
    }

    public function addOccurrence(Occurrence $occurrence): self
    {
        if (!$this->occurrences->contains($occurrence)) {
            $this->occurrences[] = $occurrence;
            $occurrence->setSpecies($this);
        }

        return $this;
    }

    public function removeOccurrence(Occurrence $occurrence): self
    {
        if ($this->occurrences->contains($occurrence)) {
            $this->occurrences->removeElement($occurrence);
            // set the owning side to null (unless already changed)
            if ($occurrence->getSpecies() === $this) {
                $occurrence->setSpecies(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SpeciesImage[]
     */
    public function getSpeciesImages(): Collection
    {
        return $this->speciesImages;
    }

    public function addSpeciesImage(SpeciesImage $speciesImage): self
    {
        if (!$this->speciesImages->contains($speciesImage)) {
            $this->speciesImages[] = $speciesImage;
            $speciesImage->setSpecies($this);
        }

        return $this;
    }

    public function removeSpeciesImage(SpeciesImage $speciesImage): self
    {
        if ($this->speciesImages->contains($speciesImage)) {
            $this->speciesImages->removeElement($speciesImage);
            // set the owning side to null (unless already changed)
            if ($speciesImage->getSpecies() === $this) {
                $speciesImage->setSpecies(null);
            }
        }

        return $this;
    }




}
