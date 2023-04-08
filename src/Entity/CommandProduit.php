<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use APP\Repository\CommandeProduitRepository;

/**
 * CommandProduit
 *
 * @ORM\Table(name="command_produit", indexes={@ORM\Index(name="id_cmd", columns={"id_cmd"})})
 * @ORM\Entity
 */
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
    private $refProduit;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_cmd", type="date", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateCmd = 'CURRENT_TIMESTAMP';

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

    public function getRefProduit(): ?string
    {
        return $this->refProduit;
    }

    public function setRefProduit(string $refProduit): self
    {
        $this->refProduit = $refProduit;

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
