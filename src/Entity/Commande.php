<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * Commande
 *
 * @ORM\Table(name="commande", uniqueConstraints={@ORM\UniqueConstraint(name="id_cmdunique", columns={"id_cmd", "id_panier"})}, indexes={@ORM\Index(name="fk_idpaniera", columns={"id_panier"})})
 * @ORM\Entity
 */
#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_cmd", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[Groups("commandes")]

    private $idCmd;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=30, nullable=false, options={"default"="En attente"})
     */
    #[Groups("commandes")]

    private $etat = 'En attente';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_cmd", type="date", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    #[Groups("commandes")]

    private ?DateTimeInterface $dateCmd;
    public function __construct()
    {
        $this->dateCmd = new \DateTime();
    }

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float", precision=10, scale=0, nullable=false)
     */
    #[Groups("commandes")]

    private $total;


    /**
     * @var string|null
     *
     * @ORM\Column(name="adresse", type="string", length=50, nullable=true)
     * @Assert\Length(
     *      min = 6,
     *      max = 60,
     *      minMessage = "L'adresse doit contenir au moins {{ limit }} caractères.",
     *      maxMessage = "L'adresse ne peut pas contenir plus de {{ limit }} caractères."
     * )
     */
    #[Groups("commandes")]

    #[Assert\NotBlank(message: "this field should not be empty")]

    private $adresse;


    /**
     * @var \Panier
     *
     * @ORM\ManyToOne(targetEntity="Panier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_panier", referencedColumnName="id_panier")
     * })
     *   
     */

    #[Groups("commandes")]


    private $idPanier;

    private $email;

    private $numtel;

    private $nom;
    public function getNom(): ?string
    {
        return $this->nom;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function getNumtel(): ?int
    {
        return $this->numtel;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }
    public function setNumtel(int $numtel): self
    {
        $this->numtel = $numtel;

        return $this;
    }

    public function getIdCmd(): ?int
    {
        return $this->idCmd;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDateCmd(): ?\DateTimeInterface
    {
        return $this->dateCmd;
    }

    public function setDateCmd(\DateTimeInterface $dateCmd): self
    {
        $this->dateCmd = $dateCmd;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getIdPanier(): ?Panier
    {
        return $this->idPanier;
    }

    public function setIdPanier(?Panier $idPanier): self
    {
        $this->idPanier = $idPanier;

        return $this;
    }

    public function __toString(): string
    {
        return $this->idCmd . " ";
    }

}