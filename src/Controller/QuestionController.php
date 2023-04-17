<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\QuestionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\QuestionsRepository;

#[Route('/question')]
class QuestionController extends AbstractController
{
    #[Route('/quiz/{idQuiz}/questions', name: 'app_question_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, $idQuiz): Response
    {
        $questions = $entityManager
            ->getRepository(Question::class)
            ->findBy(['idQuiz' => $idQuiz]);

        $quiz = $entityManager
            ->getRepository(Quiz::class)
            ->findOneBy(['idQuiz' => $idQuiz]);

        return $this->render('question/index.html.twig', [
            'questions' => $questions,
            'quiz' => $quiz,
        ]);
    }




    #[Route('/quiz/{idQuiz}/questionsfront', name: 'app_question_indecx', methods: ['GET'])]
    public function indexfront(EntityManagerInterface $entityManager, $idQuiz): Response
    {
        $questions = $entityManager
            ->getRepository(Question::class)
            ->findBy(['idQuiz' => $idQuiz]);

        $quiz = $entityManager
            ->getRepository(Quiz::class)
            ->findOneBy(['idQuiz' => $idQuiz]);

        return $this->render('quizFront/jouer.html.twig', [
            'questions' => $questions,
            'quiz' => $quiz,
        ]);
    }



    #[Route('/quiz/{idQuiz}/questions/new', name: 'app_question_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
    {
        $question = new Question();
        $question->setIdQuiz($quiz); // on lie la question au quiz en question
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('app_question_index', ['idQuiz' => $quiz->getIdQuiz()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('question/new.html.twig', [
            'question' => $question,
            'quiz' => $quiz, // on envoie aussi l'objet quiz au template pour pouvoir l'afficher
            'form' => $form,
        ]);
    }


    #[Route('/{idQuestion}', name: 'app_question_show', methods: ['GET'])]
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }


    #[Route('/{idQuestion}/edit', name: 'app_question_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();


            return $this->redirectToRoute('app_question_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('question/edit.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }


    #[Route('/quiz/{idQuiz}/question/{idQuestion}', name: 'app_question_delete', methods: ['POST'])]
    public function delete(Request $request, $idQuiz, Question $question, EntityManagerInterface $entityManager): Response
    {
        $quiz = $entityManager->getRepository(Quiz::class)->find($idQuiz);
        if ($this->isCsrfTokenValid('delete' . $question->getIdQuestion(), $request->request->get('_token'))) {
            $entityManager->remove($question);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_question_index', ['idQuiz' => $quiz->getIdQuiz()]);
    }



    #[Route('/{idQuiz}', name: 'app_questions', methods: ['GET'])]

    public function index1(QuestionsRepository $questionRepository, Quiz $quiz): Response
    {


        $questions = $questionRepository->findByQuiz($quiz);

        //dd($services);
        return $this->render('affichage.html.twig', [
            //'services' => 'ServiceFrontController',
            'questions' => $questions
        ]);
    }

    #[Route('/{idQuiz}/{idQuestion}/edit', name: 'app_question_edit1', methods: ['GET', 'POST'])]
    public function edit1(Request $request, $idQuiz, $idQuestion, EntityManagerInterface $entityManager): Response
    {
        $quiz = $entityManager->getRepository(Quiz::class)->find($idQuiz);
        $question = $entityManager->getRepository(Question::class)->find($idQuestion);

        // Vérifiez que la question appartient bien au quiz spécifié
        if ($question->getIdQuiz()->getIdQuiz() != $quiz->getIdQuiz()) {
            return $this->redirectToRoute('app_quiz_show', ['idQuiz' => $quiz->getIdQuiz()]);
        }

        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_question_index', ['idQuiz' => $quiz->getIdQuiz()]);
        }

        return $this->renderForm('question/edit.html.twig', [
            'quiz' => $quiz,
            'question' => $question,
            'form' => $form,
        ]);
    }
}
