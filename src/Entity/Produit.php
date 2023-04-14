<?php

namespace App\Entity;
use App\Entity\Categorie;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;
use Symfony\Component\Validator\Constraints as Assert;




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
     */
    #[Assert\NotBlank(message: "Ce champ est obligatoire.")]
    #[Assert\Regex(pattern: '/^TUN619.*/',message: "La référence produit doit commencer par 'TUN619'.")]
    private $ref_produit;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=50, nullable=false)
     */
    #[Assert\Length(min:6, minMessage:"La déscription doit contenir '{{ limit }}' lettres", max:100,maxMessage:"La déscription doit contenir '{{ limit }}' lettres")]
    #[Assert\NotBlank(message:"ce champs est obligatoire !")]
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=false)
     */
    #[Assert\Length(min:10, minMessage:"La déscription doit contenir '{{ limit }}' lettres", max:100,maxMessage:"La déscription doit contenir '{{ limit }}' lettres")]
    #[Assert\NotBlank(message:"ce champs est obligatoire !")]
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
    #[Assert\PositiveOrZero(message: 'Le prix doit être un nombre positif ou zéro.')]
    private $prixVente;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite = '0';

 /**
     * @var \Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie" , inversedBy="produits")
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
    private $commentaires = [];


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->idPanier = new ArrayCollection();
       
    }
    

    public function getRef_produit(): ?string
    {
        return $this->ref_produit;
    }
    public function getRefProduit(): ?string
{
    return $this->ref_produit;
} 
public function setRefProduit($ref_produit) {
    $this->ref_produit = $ref_produit;
}

public function setRef_produit($ref_produit) {
    $this->ref_produit = $ref_produit;
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
       /**
     * Get the full path of the product image
     */
    public function getImagePath(): ?string
    {
        return $this->image ? '/images' . $this->image : null;
    }

    public function getPrixVente(): ?float
    {
        return $this->prixVente;
    }

    public function setPrixVente(float $prixVente): self
    {
        $this->prixVente = $prixVente;

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
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return new ArrayCollection($this->commentaires);
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
