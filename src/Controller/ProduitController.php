<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use App\Repository\LivraisonRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(): Response
    {
        return $this->render('add.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }
    #[Route('/dashboard', name: 'app_commande_dashboard')]
    public function stats2( CommandeRepository $commandeRepository, LivraisonRepository $livraisonRepository): Response
    {
    //    $currentMonthName = (new \DateTime())->format('F');
        $somme = $commandeRepository->countSomme();
        $sommeFormatted = number_format($somme, 2);
        $totalpendingorder=$commandeRepository->countPending();
        $totalpendinglivraison=$livraisonRepository->countPendingLiv();
      
        return $this->render('dashboard.html.twig', [

            'somme' => $sommeFormatted,
            'totalpendingorder'=> $totalpendingorder,
            'totalpendinglivraison'=> $totalpendinglivraison,

        ]);
    }
}
