<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Bonplan
 *
 * @ORM\Table(name="bonplan")
 * @ORM\Entity
 */
class Bonplan
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_bonplan", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idBonplan;

    /**
     * @var string
     *
     * @ORM\Column(name="titre_bonplan", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Ce champ est obligatoire!")
     * @Assert\Regex(pattern="/^[a-zA-Z\s]+$/",message="Le titre ne doit contenir que des lettres.")
     */
    private $titreBonplan;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description_bonplan", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Ce champ est obligatoire!")
     */
    private $descriptionBonplan;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type_bonplan", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Ce champ est obligatoire!")
     */
    private $typeBonplan;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image_bonplan", type="string", length=255, nullable=true)
    
     */
    private $imageBonplan;

    public function getIdBonplan(): ?int
    {
        return $this->idBonplan;
    }

    public function getTitreBonplan(): ?string
    {
        return $this->titreBonplan;
    }

    public function setTitreBonplan(string $titreBonplan): self
    {
        $this->titreBonplan = $titreBonplan;

        return $this;
    }

    public function getDescriptionBonplan(): ?string
    {
        return $this->descriptionBonplan;
    }

    public function setDescriptionBonplan(?string $descriptionBonplan): self
    {
        $this->descriptionBonplan = $descriptionBonplan;

        return $this;
    }

    public function getTypeBonplan(): ?string
    {
        return $this->typeBonplan;
    }

    public function setTypeBonplan(?string $typeBonplan): self
    {
        $this->typeBonplan = $typeBonplan;

        return $this;
    }

    public function getImageBonplan(): ?string
    {
        return $this->imageBonplan;
    }

    public function setImageBonplan(?string $imageBonplan): self
    {
        $this->imageBonplan = $imageBonplan;

        return $this;
    }


}