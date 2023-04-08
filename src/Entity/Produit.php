<?php

namespace App\Entity;
use App\Entity\Categorie;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;



/**
 * Produit
 *
 * @ORM\Table(name="produit", indexes={@ORM\Index(name="id_categorie", columns={"id_categorie"})})
 * @ORM\Entity
 */
#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    /**
     * @var string
     *
     * @ORM\Column(name="ref_produit", type="string", length=30, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ref_produit;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=50, nullable=false)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=100, nullable=false)
     */
    private $image;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_vente", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix_vente;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite = '0';

    /**
     * @var \Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categorie", referencedColumnName="id_categorie")
     * })
     */
    private $categorie;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Panier", mappedBy="ref_produit")
     */
    private $idPanier = array();

    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'produit')]
    private Collection $commentaires;


    /**
     * Constructor
     */
    public function __construct2()
{
    $this->commentaires = new ArrayCollection();
}

    public function __construct()
    {
        $this->idPanier = new ArrayCollection();
    }

    public function getref_produit(): ?string
    {
        return $this->ref_produit;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getprix_vente(): ?float
    {
        return $this->prix_vente;
    }

    public function setprix_vente(float $prix_vente): self
    {
        $this->prix_vente = $prix_vente;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getIdPanier(): Collection
    {
        return $this->idPanier;
    }

    public function addIdPanier(Panier $idPanier): self
    {
        if (!$this->idPanier->contains($idPanier)) {
            $this->idPanier->add($idPanier);
            $idPanier->addrefproduit($this);
        }

        return $this;
    }

    public function removeIdPanier(Panier $idPanier): self
    {
        if ($this->idPanier->removeElement($idPanier)) {
            $idPanier->removerefproduit($this);
        }

        return $this;
    }
            /**
     * @return Collection<int, commentaire>
     */
    public function getCommentaire(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        $this->commentaires->removeElement($commentaire);

        return $this;
    }



}
