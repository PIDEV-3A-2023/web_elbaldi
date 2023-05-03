<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Form\PromotionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\Utilisateur;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;




#[Route('/promotion')]
class PromotionController extends AbstractController
{

    #[Route('/check-promo', name: 'check_promo', methods: ['GET', 'POST'])]
    public function verifierCodePromo(Request $request)
    {
        $promo_exist = null;

        if ($request->getMethod() == 'POST') {
            $code_promo = $request->request->get('code_promo');

            // on utilise l'ORM Doctrine pour récupérer une instance de la classe "Promotion" qui a le même code promo que 
            //celui envoyé via le formulaire et qui a l'utilisateur ayant l'ID 2499 associé.
            // Si aucune instance n'est trouvée, la variable $promo sera null.
            $promo = $this->getDoctrine()
                ->getRepository(Promotion::class)
                ->findOneBy([
                    'codePromo' => $code_promo,
                    'idUser' => 2499
                ]);

            //  on assigne à la variable $promo_exist la valeur true si l'instance trouvée précédemment n'est pas null, ou false sinon.
            $promo_exist = ($promo !== null);
        }


        return $this->render('promotion/check.html.twig', [
            'promo_exist' => $promo_exist

        ]);
    }




    #[Route('/promotion/code', name: 'promo', methods: ['POST'])]

    public function ajouterCodePromo(Request $request, EntityManagerInterface $entityManager)
    {
        $codePromo = $request->request->get('codepromo');

        // Enregistrement de la promotion dans la base de données
        $promotion = new Promotion();
        $promotion->setCodePromo($codePromo);
        $promotion->setTaux(0.15);

        $user = $entityManager
            ->getRepository(Utilisateur::class)
            ->find(2499); // Récupérer l'utilisateur ayant l'id_user de 2499
        $promotion->setIdUser($user);

        $entityManager->persist($promotion);
        $entityManager->flush();

        // Retourner une réponse JSON indiquant que l'opération a réussi
        return new JsonResponse(array('success' => true));
    }








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

    #[Route('/new', name: 'app_promotion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération du code promo à partir du formulaire
            $codePromo = $form->get('codePromo')->getData();

            // Association du code promo à la promotion
            $promotion->setCodePromo($codePromo);

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
