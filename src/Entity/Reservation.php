<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Reservation
 *
 * @ORM\Table(name="reservation", indexes={@ORM\Index(name="cle_etrangere", columns={"id_bonplan"}), @ORM\Index(name="reservation_ibfk_1", columns={"id_user"})})
 * @ORM\Entity
 */
class Reservation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_reservation", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idReservation;

    /**
     * @var int
     *
     * @ORM\Column(name="nombre_personnes", type="integer", nullable=false)
     *  @Assert\NotBlank(message="Ce champ est obligatoire!")
     *  @Assert\Regex(pattern="/^[0-9]+$/",message="Le nombre de personnes ne doit contenir que des chiffres.")
     */
    private $nombrePersonnes;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_reservation", type="date", nullable=false)
     *  @Assert\NotBlank(message="Ce champ est obligatoire!")
     */
    private $dateReservation;

    /**
     * @var string
     *
     * @ORM\Column(name="statut_reservation", type="string", length=255, nullable=false)
     *  @Assert\NotBlank(message="Ce champ est obligatoire!")
     */
    private $statutReservation;

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

    public function getIdReservation(): ?int
    {
        return $this->idReservation;
    }

    public function getNombrePersonnes(): ?int
    {
        return $this->nombrePersonnes;
    }

    public function setNombrePersonnes(int $nombrePersonnes): self
    {
        $this->nombrePersonnes = $nombrePersonnes;

        return $this;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->dateReservation;
    }

    public function setDateReservation(\DateTimeInterface $dateReservation): self
    {
        $this->dateReservation = $dateReservation;

        return $this;
    }

    public function getStatutReservation(): ?string
    {
        return $this->statutReservation;
    }

    public function setStatutReservation(string $statutReservation): self
    {
        $this->statutReservation = $statutReservation;

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
