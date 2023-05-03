<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\QuizRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * Quiz
 *
 * @ORM\Table(name="quiz")
 * @ORM\Entity
 *  @UniqueEntity(fields={"nom"}, message="Ce nom est déjà utilisé")
 */

#[ORM\Entity(repositoryClass: QuizRepository::class)]
class Quiz
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_quiz", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idQuiz;

    /**
     * @var string
     *
     * @ORM\Column(name="difficulte", type="string", length=255, nullable=false)
     */
    private $difficulte;

    /**
     * @var int|null
     *
     * @ORM\Column(name="score", type="integer", nullable=true)
     */
    private $score;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=100, nullable=false)
     * @Assert\NotBlank(message="Ce champ est obligatoire!")
     * @Assert\Regex(pattern="/^[a-zA-Z]+$/", message="Le nom ne doit contenir que des lettres")
     */


    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=50, nullable=true)
     */
    private $image;

    public function getIdQuiz(): ?int
    {
        return $this->idQuiz;
    }

    public function getDifficulte(): ?string
    {
        return $this->difficulte;
    }

    public function setDifficulte(string $difficulte): self
    {
        $this->difficulte = $difficulte;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
