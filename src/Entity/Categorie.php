<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity
 * @UniqueEntity(fields={"nomCategorie"}, message="Cette catégorie existe déjà")
 */
#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_categorie", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id_categorie;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_categorie", type="string", length=255, nullable=false)
     */
    #[Assert\NotBlank(message: "Ce champs est obligatoire !")]
    #[Assert\Regex(pattern: "/^[a-zA-Z\s]+$/", message: "Le nom ne doit contenir que des lettres")]
    private $nomCategorie;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="string", length=255, nullable=false)
     */
    #[Assert\Length(min: 5, minMessage: "La description doit contenir au minimum'{{ limit }}' lettres", max: 100, maxMessage: "La description doit contenir au maximum'{{ limit }}' lettres")]
    #[Assert\NotBlank(message: "Ce champs est obligatoire !")]
    private $description;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Categorie::class, orphanRemoval: true, cascade: ['null'])]
    private Collection $produits;


    public function getid_categorie(): ?int
    {
        return $this->id_categorie;
    }

    public function getnomCategorie(): ?string
    {
        return $this->nomCategorie;
    }

    public function setnomCategorie(string $nomCategorie): self
    {
        $this->nomCategorie = $nomCategorie;

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
    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setCategorie($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getCategorie() === $this) {
                $produit->setCategorie(null);
            }
        }

        return $this;
    }

    public function getIdCategorie(): ?int
    {
        return $this->id_categorie;
    }
}
