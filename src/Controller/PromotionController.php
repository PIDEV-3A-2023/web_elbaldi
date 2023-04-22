<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Form\PromotionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/promotion')]
class PromotionController extends AbstractController
{
    #[Route('/', name: 'app_promotion_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $promotions = $entityManager
            ->getRepository(Promotion::class)
            ->findAll();

        return $this->render('promotion/index.html.twig', [
            'promotions' => $promotions,
        ]);
    }

    // #[Route('/new', name: 'app_promotion_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $promotion = new Promotion();
    //     $form = $this->createForm(PromotionType::class, $promotion);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Récupération du code promo à partir du formulaire
    //         $codePromo = $form->get('codePromo')->getData();

    //         // Association du code promo à la promotion
    //         $promotion->setCodePromo($codePromo);

    //         $entityManager->persist($promotion);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_promotion_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('promotion/new.html.twig', [
    //         'promotion' => $promotion,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/new', name: 'app_promotion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $promotion = new Promotion();

        // Génération du code promo en fonction du score
        $score = 5; // Exemple : le score du quiz
        $promoCode = '';
        $characters = '0123456789';

        if ($score >= 1) {
            $promoCode .= 'Elbaldi';
        } else if ($score >= 5) {
            $promoCode .= 'Elbaldi';
        } else if ($score >= 3) {
            $promoCode .= 'Elbaldi';
        }

        for ($i = 0; $i < 4; $i++) {
            $promoCode .= $characters[rand(0, strlen($characters) - 1)];
        }

        // Association du code promo à la promotion
        $promotion->setCodePromo($promoCode);

        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($promotion);
            $entityManager->flush();

            return $this->redirectToRoute('app_promotion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('promotion/new.html.twig', [
            'promotion' => $promotion,
            'form' => $form,
        ]);
    }

    #[Route('/{idPromotion}', name: 'app_promotion_show', methods: ['GET'])]
    public function show(Promotion $promotion): Response
    {
        return $this->render('promotion/show.html.twig', [
            'promotion' => $promotion,
        ]);
    }

    // #[Route('/new', name: 'app_promotion_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $promotion = new Promotion();
    //     $form = $this->createForm(PromotionType::class, $promotion);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Get the currently logged-in user
    //         $user = $this->getUser();
    //         // Set the user for the promotion
    //         $promotion->setIdUser($user);
    //         // Persist the promotion
    //         $entityManager->persist($promotion);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_promotion_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('promotion/new.html.twig', [
    //         'promotion' => $promotion,
    //         'form' => $form,
    //     ]);
    // }








    #[Route('/{idPromotion}/edit', name: 'app_promotion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Promotion $promotion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_promotion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('promotion/edit.html.twig', [
            'promotion' => $promotion,
            'form' => $form,
        ]);
    }

    #[Route('/{idPromotion}', name: 'app_promotion_delete', methods: ['POST'])]
    public function delete(Request $request, Promotion $promotion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $promotion->getIdPromotion(), $request->request->get('_token'))) {
            $entityManager->remove($promotion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_promotion_index', [], Response::HTTP_SEE_OTHER);
    }
}
