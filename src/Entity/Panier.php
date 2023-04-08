<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Panier
 *
 * @ORM\Table(name="panier", uniqueConstraints={@ORM\UniqueConstraint(name="id_user", columns={"id_user"})})
 * @ORM\Entity
 */
class Panier
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_panier", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPanier;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nombre_article", type="integer", nullable=true)
     */
    private $nombreArticle = '0';

    /**
     * @var float|null
     *
     * @ORM\Column(name="total_panier", type="float", precision=10, scale=0, nullable=true)
     */
    private $totalPanier = '0';

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Produit", inversedBy="idPanier")
     * @ORM\JoinTable(name="panier_produit",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_panier", referencedColumnName="id_panier")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="ref_produit", referencedColumnName="ref_produit")
     *   }
     * )
     */
    private $refProduit = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->refProduit = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdPanier(): ?int
    {
        return $this->idPanier;
    }

    public function getNombreArticle(): ?int
    {
        return $this->nombreArticle;
    }

    public function setNombreArticle(?int $nombreArticle): self
    {
        $this->nombreArticle = $nombreArticle;

        return $this;
    }

    public function getTotalPanier(): ?float
    {
        return $this->totalPanier;
    }

    public function setTotalPanier(?float $totalPanier): self
    {
        $this->totalPanier = $totalPanier;

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

    /**
     * @return Collection<int, Produit>
     */
    public function getRefProduit(): Collection
    {
        return $this->refProduit;
    }

    public function addRefProduit(Produit $refProduit): self
    {
        if (!$this->refProduit->contains($refProduit)) {
            $this->refProduit->add($refProduit);
        }

        return $this;
    }

    public function removeRefProduit(Produit $refProduit): self
    {
        $this->refProduit->removeElement($refProduit);

        return $this;
    }

}
