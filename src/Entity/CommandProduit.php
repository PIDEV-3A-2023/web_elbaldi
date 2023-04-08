<?php

namespace App\Entity;
use App\Entity\Commande;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeProduitRepository;

/**
 * CommandProduit
 *
 * @ORM\Table(name="command_produit", indexes={@ORM\Index(name="id_cmd", columns={"id_cmd"})})
 * @ORM\Entity
 */
#[ORM\Entity(repositoryClass: CommandeProduitRepository::class)]
class CommandProduit
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ref_produit", type="string", length=30, nullable=false)
     */
    private $ref_Produit;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_cmd", type="date", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $date_cmd = 'CURRENT_TIMESTAMP';

    /**
     * @var \Commande
     *
     * @ORM\ManyToOne(targetEntity="Commande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_cmd", referencedColumnName="id_cmd")
     * })
     */
    private $idCmd;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRef_Produit(): ?string
    {
        return $this->ref_Produit;
    }

    public function setRef_Produit(string $ref_Produit): self
    {
        $this->ref_Produit = $ref_Produit;

        return $this;
    }

    public function getDateCmd(): ?\DateTimeInterface
    {
        return $this->date_cmd;
    }

    public function setDateCmd(\DateTimeInterface $date_cmd): self
    {
        $this->date_cmd = $date_cmd;

        return $this;
    }

    public function getIdCmd(): ?Commande
    {
        return $this->idCmd;
    }

    public function setIdCmd(?Commande $idCmd): self
    {
        $this->idCmd = $idCmd;

        return $this;
    }


}
