<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Avis
 *
 * @ORM\Table(name="avis", indexes={@ORM\Index(name="FK2", columns={"id_bonplan"}), @ORM\Index(name="FK1", columns={"id_user"})})
 * @ORM\Entity
 */
class Avis
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_avis", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAvis;

    /**
     * @var float
     *
     * @ORM\Column(name="note_avis", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteAvis;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_avis", type="date", nullable=false)
     */
    private $dateAvis;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     */
    private $idUser;

    /**
     * @var \Bonplan
     *
     * @ORM\ManyToOne(targetEntity="Bonplan")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_bonplan", referencedColumnName="id_bonplan")
     * })
     */
    private $idBonplan;

    public function getIdAvis(): ?int
    {
        return $this->idAvis;
    }

    public function getNoteAvis(): ?float
    {
        return $this->noteAvis;
    }

    public function setNoteAvis(float $noteAvis): self
    {
        $this->noteAvis = $noteAvis;

        return $this;
    }

    public function getDateAvis(): ?\DateTimeInterface
    {
        return $this->dateAvis;
    }

    public function setDateAvis(\DateTimeInterface $dateAvis): self
    {
        $this->dateAvis = $dateAvis;

        return $this;
    }

    public function getIdUser(): ?Utilisateur
    {
        return $this->idUser;
    }

    public function setIdUser(?Utilisateur $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdBonplan(): ?Bonplan
    {
        return $this->idBonplan;
    }

    public function setIdBonplan(?Bonplan $idBonplan): self
    {
        $this->idBonplan = $idBonplan;

        return $this;
    }


}