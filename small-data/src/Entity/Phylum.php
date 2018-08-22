<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhylumRepository")
 */
class Phylum
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phylumNameWorms;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Species", mappedBy="phylum")
     */
    private $species;

    public function __construct()
    {
        $this->species = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPhylumNameWorms(): ?string
    {
        return $this->phylumNameWorms;
    }

    public function setPhylumNameWorms(string $phylumNameWorms): self
    {
        $this->phylumNameWorms = $phylumNameWorms;

        return $this;
    }

    /**
     * @return Collection|Species[]
     */
    public function getSpecies(): Collection
    {
        return $this->species;
    }

    public function addSpecies(Species $species): self
    {
        if (!$this->species->contains($species)) {
            $this->species[] = $species;
            $species->setPhylum($this);
        }

        return $this;
    }

    public function removeSpecies(Species $species): self
    {
        if ($this->species->contains($species)) {
            $this->species->removeElement($species);
            // set the owning side to null (unless already changed)
            if ($species->getPhylum() === $this) {
                $species->setPhylum(null);
            }
        }

        return $this;
    }
}
