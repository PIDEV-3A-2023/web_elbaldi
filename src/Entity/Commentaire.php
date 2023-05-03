<?php

namespace App\Entity;
use App\Entity\Utilisateur;
use App\Entity\Produit;
use Doctrine\ORM\Mapping as ORM;
<<<<<<< HEAD
use App\Repository\CommentaireRepository;
=======
>>>>>>> origin/selim

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire", indexes={@ORM\Index(name="id_user", columns={"id_user"}), @ORM\Index(name="ref_produit", columns={"ref_produit"})})
 * @ORM\Entity
 */
<<<<<<< HEAD
#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
=======
>>>>>>> origin/selim
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
<<<<<<< HEAD
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur",inversedBy="commentaires",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     */
    private $user;

    /**
=======
>>>>>>> origin/selim
     * @var \Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit", inversedBy="commentaires")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ref_produit", referencedColumnName="ref_produit")
     * })
     */
    private $produit;

<<<<<<< HEAD
    public function getId_commentaire(): ?int
=======
    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     */
    private $idUser;

    public function getIdCommentaire(): ?int
>>>>>>> origin/selim
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

    public function getDate_comm(): ?\DateTimeInterface
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

    public function setUser(?Utilisateur $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getIdCommentaire(): ?int
    {
        return $this->id_commentaire;
    }

    public function getDateComm(): ?\DateTimeInterface
    {
        return $this->date_comm;
    }

    public function setDateComm(\DateTimeInterface $date_comm): self
    {
<<<<<<< HEAD
        $this->date_comm = $date_comm;
=======
        $this->dateComm = $dateComm;

        return $this;
    }

    public function getRefProduit(): ?Produit
    {
        return $this->refProduit;
    }

    public function setRefProduit(?Produit $refProduit): self
    {
        $this->refProduit = $refProduit;
>>>>>>> origin/selim

        return $this;
    }

<<<<<<< HEAD
    
=======
    public function getIdUser(): ?Utilisateur
    {
        return $this->idUser;
    }

    public function setIdUser(?Utilisateur $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

>>>>>>> origin/selim

}
