<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpeciesImageRepository")
 */
class SpeciesImage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\File(maxSize="5242880",
     *      mimeTypes = {
     *          "image/png",
     *          "image/jpeg",
     *          "image/jpg",
     *          "image/gif",
     *          "application/pdf",
     *          "application/x-pdf"
     *      }
     *     )
     */
    private $speciesImageName;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Species", inversedBy="speciesImages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $species;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isForDisplay;






    public function getId()
    {
        return $this->id;
    }

    public function getSpeciesImageName(): ?string
    {
        return $this->speciesImageName;
    }

    public function setSpeciesImageName(string $speciesImageName): self
    {
        $this->speciesImageName = $speciesImageName;

        return $this;
    }

    public function getSpecies(): ?Species
    {
        return $this->species;
    }

    public function setSpecies(?Species $species): self
    {
        $this->species = $species;

        return $this;
    }

    public function getIsForDisplay(): ?bool
    {
        return $this->isForDisplay;
    }

    public function setIsForDisplay(?bool $isForDisplay): self
    {
        $this->isForDisplay = $isForDisplay;

        return $this;
    }






}
