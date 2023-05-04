<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity
 */
class Event
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEvent;

    /**
     * @var string
     * @Assert\NotBlank(message="title is required")
     * @Assert\Length(min=10)
     * * @Assert\Length(max=50)
     * @ORM\Column(name="title_event", type="string", length=255, nullable=false)
     */
    private $titleEvent;

    /**
     * @var string
     * @Assert\Image
     * @ORM\Column(name="image_event", type="string", length=255, nullable=false)
     */
    private $imageEvent;

    /**
     * @var string
     * @Assert\NotBlank(message="description is required")
     * @ORM\Column(name="description_event", type="string", length=255, nullable=false)
     */
    private $descriptionEvent;

    /**
     * @var \DateTime
     * @ORM\Column(name="time_event", type="datetime", nullable=false)
     */
    
    private $timeEvent;
     /**
     * @var string
     * @Assert\NotBlank(message="Organisation is required")
     * @Assert\Length(min=3)
     * @Assert\Length(max=10)
     * @ORM\Column(name="Organisation", type="string", length=255, nullable=false)
     */
    private $Organisation;
    

    public function getIdEvent(): ?int
    {
        return $this->idEvent;
    }

    public function getTitleEvent(): ?string
    {
        return $this->titleEvent;
    }

    public function setTitleEvent(string $titleEvent): self
    {
        $this->titleEvent = $titleEvent;

        return $this;
    }

    public function getImageEvent(): ?string
    {
        return $this->imageEvent;
    }

    public function setImageEvent(string $imageEvent): self
    {
        $this->imageEvent = $imageEvent;

        return $this;
    }

    public function getDescriptionEvent(): ?string
    {
        return $this->descriptionEvent;
    }

    public function setDescriptionEvent(string $descriptionEvent): self
    {
        $this->descriptionEvent = $descriptionEvent;

        return $this;
    }

    public function getTimeEvent(): ?\DateTimeInterface
    {
        return $this->timeEvent;
    }

    public function setTimeEvent(\DateTimeInterface $timeEvent): self
    {
        $this->timeEvent = $timeEvent;

        return $this;
    }
    public function getOrganisation(): ?string
    {
        return $this->Organisation;
    }

    public function setOrganisation(string $Organisation): self
    {
        $this->Organisation = $Organisation;

        return $this;
    }
    public function __toString()
    {
        return $this->idEvent;
    }




}
