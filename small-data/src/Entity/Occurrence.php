<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OccurrenceRepository")
 */
class Occurrence
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $eventDate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $vernacularName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $scientificNameAtCollection;

    /**
     * @ORM\Column(type="float")
     */
    private $decimalLongitude;

    /**
     * @ORM\Column(type="float")
     */
    private $decimalLatitude;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $locality;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $locationId;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $occurrenceRemarks;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Url()
     */
    private $associatedMediaUrl;

    /**
     * @ORM\Column(type="datetime")
     */
    private $occurrenceCreatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Species", inversedBy="occurrences")
     */
    private $species;

    public function getId()
    {
        return $this->id;
    }

    public function getEventDate(): ?\DateTimeInterface
    {
        return $this->eventDate;
    }

    public function setEventDate(\DateTimeInterface $eventDate): self
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    public function getVernacularName(): ?string
    {
        return $this->vernacularName;
    }

    public function setVernacularName(?string $vernacularName): self
    {
        $this->vernacularName = $vernacularName;

        return $this;
    }

    public function getScientificNameAtCollection(): ?string
    {
        return $this->scientificNameAtCollection;
    }

    public function setScientificNameAtCollection(?string $scientificNameAtCollection): self
    {
        $this->scientificNameAtCollection = $scientificNameAtCollection;

        return $this;
    }

    public function getDecimalLongitude(): ?float
    {
        return $this->decimalLongitude;
    }

    public function setDecimalLongitude(float $decimalLongitude): self
    {
        $this->decimalLongitude = $decimalLongitude;

        return $this;
    }

    public function getDecimalLatitude(): ?float
    {
        return $this->decimalLatitude;
    }

    public function setDecimalLatitude(float $decimalLatitude): self
    {
        $this->decimalLatitude = $decimalLatitude;

        return $this;
    }

    public function getLocality(): ?string
    {
        return $this->locality;
    }

    public function setLocality(?string $locality): self
    {
        $this->locality = $locality;

        return $this;
    }

    public function getLocationId(): ?string
    {
        return $this->locationId;
    }

    public function setLocationId(?string $locationId): self
    {
        $this->locationId = $locationId;

        return $this;
    }

    public function getOccurrenceRemarks(): ?string
    {
        return $this->occurrenceRemarks;
    }

    public function setOccurrenceRemarks(?string $occurrenceRemarks): self
    {
        $this->occurrenceRemarks = $occurrenceRemarks;

        return $this;
    }

    public function getAssociatedMediaUrl(): ?string
    {
        return $this->associatedMediaUrl;
    }

    public function setAssociatedMediaUrl(?string $associatedMediaUrl): self
    {
        $this->associatedMediaUrl = $associatedMediaUrl;

        return $this;
    }

    public function getOccurrenceCreatedAt(): ?\DateTimeInterface
    {
        return $this->occurrenceCreatedAt;
    }

    public function setOccurrenceCreatedAt(\DateTimeInterface $occurrenceCreatedAt): self
    {
        $this->occurrenceCreatedAt = $occurrenceCreatedAt;

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
}
