<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Participation
 *
 * @ORM\Table(name="participation", indexes={@ORM\Index(name="id_evenement_id", columns={"id_evenement_id"}), @ORM\Index(name="id_client_id", columns={"id_client_id"})})
 * @ORM\Entity
 */
class Participation
{
    /**
    * @var int
    
    * @ORM\Column(name="id", type="integer", nullable=false)
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="IDENTITY")
    */
    private $idParticipation;

    /**
    * @var string
    
    * @ORM\Column(name="Nom_client", type="string", length=255, nullable=false)
    * @Assert\NotBlank(message="title is required")
    */
    private $nomClient;

    /**
     * @var string
     * @ORM\Column(name="Prenom_client", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="title is required")
     */
    private $prenomClient;

    /**
     * @var string
     * @Assert\NotBlank(message="title is required")
     * @ORM\Column(name="Nom_evenement", type="string", length=255, nullable=false)
     */
    private $nomEvenement;

    /**
     * @var \DateTime
     * @Assert\NotBlank(message="title is required")
     * @ORM\Column(name="Date", type="datetime",length=255, nullable=false)
     */
    private $date;

    /**
     * @var \Event
     * @Assert\NotBlank(message="title is required")
     * @ORM\ManyToOne(targetEntity="Event")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_evenement_id", referencedColumnName="id")
     * })
     */
    private $idEvenement;

    /**
     * @var \Client
     *  @Assert\NotBlank(message="title is required")
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_client_id", referencedColumnName="id")
     * })
     */
    private $idClient;

    public function getIdParticipation(): ?int
    {
        return $this->idParticipation;
    }

    public function getNomClient(): ?string
    {
        return $this->nomClient;
    }

    public function setNomClient(string $nomClient): self
    {
        $this->nomClient = $nomClient;

        return $this;
    }

    public function getPrenomClient(): ?string
    {
        return $this->prenomClient;
    }

    public function setPrenomClient(string $prenomClient): self
    {
        $this->prenomClient = $prenomClient;

        return $this;
    }

    public function getNomEvenement(): ?string
    {
        return $this->nomEvenement;
    }

    public function setNomEvenement(string $nomEvenement): self
    {
        $this->nomEvenement = $nomEvenement;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIdEvenement(): ?Event
    {
        return $this->idEvenement;
    }

    public function setIdEvenement(?Event $idEvenement): self
    {
        $this->idEvenement = $idEvenement;

        return $this;
    }

    public function getIdClient(): ?Client
    {
        return $this->idClient;
    }

    public function setIdClient(?Client $idClient): self
    {
        $this->idClient = $idClient;

        return $this;
    }





}