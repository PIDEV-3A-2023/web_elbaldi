<?php

namespace App\Entity;
use App\Entity\Utilisateur;
use App\Entity\Produit;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\CommentaireRepository;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire", indexes={@ORM\Index(name="id_user", columns={"id_user"}), @ORM\Index(name="ref_produit", columns={"ref_produit"})})
 * @ORM\Entity
 */
#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_commentaire", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id_commentaire;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="string", length=50, nullable=false)
     */
    private $contenu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_comm", type="date", nullable=false)
     */
    private $date_comm;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     */
    private $user;

    /**
     * @var \Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ref_produit", referencedColumnName="ref_produit")
     * })
     */
    private $produit;

    public function getId_commentaire(): ?int
    {
        return $this->id_commentaire;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateComm(): ?\DateTimeInterface
    {
        return $this->date_comm;
    }

    public function setDate_comm(\DateTimeInterface $date_comm): self
    {
        $this->date_comm = $date_comm;

        return $this;
    }

    public function getUser(): ?Utilisateur
    {
        return $this->user;
    }

    public function setIdUser(?Utilisateur $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setRefProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }


}
