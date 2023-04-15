<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\Question;
use App\Form\QuizType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\Persistence\ManagerRegistry;

use App\Repository\QuizRepository;
use App\Entity\QuestionsRepository;
use App\Repository\QuizQuestionsRepository;



#[Route('/quiz')]
class QuizController extends AbstractController
{
    #[Route('/', name: 'app_quiz_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $quizzes = $entityManager
            ->getRepository(Quiz::class)
            ->findAll();

        return $this->render('quiz/index.html.twig', [
            'quizzes' => $quizzes,
        ]);
    }


    #[Route('/front/', name: 'app_quiz_indexc', methods: ['GET'])]
    public function indexfront(EntityManagerInterface $entityManager): Response
    {
        $quizzes = $entityManager
            ->getRepository(Quiz::class)
            ->findAll();

        return $this->render('quizFront/index.html.twig', [
            'quizzes' => $quizzes,
        ]);
    }

    #[Route('/new', name: 'app_quiz_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $quiz = new Quiz();
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quiz);
            $entityManager->flush();

            return $this->redirectToRoute('app_quiz_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quiz/new.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
        ]);
    }

    #[Route('/{idQuiz}', name: 'app_quiz_show', methods: ['GET'])]
    public function show(Quiz $quiz): Response
    {
        return $this->render('quiz/show.html.twig', [
            'quiz' => $quiz,
        ]);
    }

    #[Route('/{idQuiz}/edit', name: 'app_quiz_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_quiz_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quiz/edit.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
        ]);
    }



    #[Route('/deleteQuiz/{idQuiz}', name: 'DeleteQuiz')]
    public function deleteQuiz($idQuiz, ManagerRegistry $doctrine): Response
    {
        //Trouver le bon Quiz
        $repoC = $doctrine->getRepository(Quiz::class);
        $quiz = $repoC->find($idQuiz);
        //Utiliser Manager pour supprimer le quiz trouvé
        $em = $doctrine->getManager();
        $em->remove($quiz);
        $em->flush();

        return $this->redirectToRoute('app_quiz_index');
    }


    #[Route('/quiz/{idQuiz}', name: 'quiz_show')]
    public function showQuiz(Quiz $quiz, ManagerRegistry $managerRegistry): Response
    {
        $questions = $managerRegistry
            ->getRepository(Question::class)
            ->findBy(['idQuiz' => $quiz->getIdQuiz()]);

        return $this->render('question/index.html.twig', [
            'quiz' => $quiz,
            'questions' => $questions,
        ]);
    }
}
