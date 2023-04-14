<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\QuestionRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Question
 *
 * @ORM\Table(name="question", indexes={@ORM\Index(name="CLE_ETRANG", columns={"id_quiz"})})
 * @ORM\Entity
 */

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_question", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idQuestion;

    /**
     * @var string
     *
     * @ORM\Column(name="difficulte", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Ce champ est obligatoire!")
     */
    private $difficulte;

    /**
     * @var string
     *
     * @ORM\Column(name="questionn", type="string", length=255, nullable=false)
     * @Assert\Length(min=6, minMessage="La question doit contenir au moins 6 lettres")
     * @Assert\NotBlank(message="Ce champ est obligatoire!")
     */
    private $questionn;

    /**
     * @var string
     *
     * @ORM\Column(name="reponse1", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Ce champ est obligatoire!")
     */
    private $reponse1;

    /**
     * @var string
     *
     * @ORM\Column(name="reponse2", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Ce champ est obligatoire!")
     */
    private $reponse2;

    /**
     * @var string
     *
     * @ORM\Column(name="reponse3", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Ce champ est obligatoire!")
     */
    private $reponse3;

    /**
     * @var string
     *
     * @ORM\Column(name="solution", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Ce champ est obligatoire!")
     
     */
    private $solution;

    /**
     * @var \Quiz
     *
     * @ORM\ManyToOne(targetEntity="Quiz")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_quiz", referencedColumnName="id_quiz")
     * })
     * @Assert\NotBlank(message="Ce champ est obligatoire!")
     */
    private $idQuiz;

    public function getIdQuestion(): ?int
    {
        return $this->idQuestion;
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

    public function getQuestionn(): ?string
    {
        return $this->questionn;
    }

    public function setQuestionn(string $questionn): self
    {
        $this->questionn = $questionn;

        return $this;
    }

    public function getReponse1(): ?string
    {
        return $this->reponse1;
    }

    public function setReponse1(string $reponse1): self
    {
        $this->reponse1 = $reponse1;

        return $this;
    }

    public function getReponse2(): ?string
    {
        return $this->reponse2;
    }

    public function setReponse2(string $reponse2): self
    {
        $this->reponse2 = $reponse2;

        return $this;
    }

    public function getReponse3(): ?string
    {
        return $this->reponse3;
    }

    public function setReponse3(string $reponse3): self
    {
        $this->reponse3 = $reponse3;

        return $this;
    }

    public function getSolution(): ?string
    {
        return $this->solution;
    }

    public function setSolution(string $solution): self
    {
        $this->solution = $solution;

        return $this;
    }

    public function getIdQuiz(): ?Quiz
    {
        return $this->idQuiz;
    }

    public function setIdQuiz(?Quiz $idQuiz): self
    {
        $this->idQuiz = $idQuiz;

        return $this;
    }
}
