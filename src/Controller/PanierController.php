<?php

namespace App\Controller;
use App\Controller\PromotionController;
use App\Entity\Panier;
use App\Form\PanierType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use App\Repository\CommandeRepository;
use App\Repository\CommentaireRepository;
use App\Entity\Commentaire;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\Console\Output\ConsoleOutput;



#[Route('/panier')]
class PanierController extends AbstractController
{
    #[Route('/', name: 'app_panier_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $paniers = $entityManager
            ->getRepository(Panier::class)
            ->findAll();

        return $this->render('panier/index.html.twig', [
            'paniers' => $paniers,
        ]);
    }

    #[Route('/new', name: 'app_panier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $panier = new Panier();
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($panier);
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('panier/new.html.twig', [
            'panier' => $panier,
            'form' => $form,
        ]);
    }

    #[Route('/{idPanier}', name: 'app_panier_show', methods: ['GET'])]
    public function show(Panier $panier): Response
    {
        return $this->render('panier/show.html.twig', [
            'panier' => $panier,
        ]);
    }

    #[Route('/{idPanier}/edit', name: 'app_panier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('panier/edit.html.twig', [
            'panier' => $panier,
            'form' => $form,
        ]);
    }

    #[Route('/{idPanier}', name: 'app_panier_delete', methods: ['POST'])]
    public function delete(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $panier->getIdPanier(), $request->request->get('_token'))) {
            $entityManager->remove($panier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/front/{idPanier}', name: 'app_panier_show_front', methods: ['GET'])]
    public function front(Panier $panier, EntityManagerInterface $entityManager): Response
    {
        $total = 5;
        foreach ($panier->getRefProduit() as $produit) {
            $total += $produit->getPrixVente()*$produit->getQuantite();
        }
        $panier->setTotalPanier($total);
        $panier->setNombreArticle($panier->getRefProduit()->count());

        $entityManager->persist($panier);
        $entityManager->flush();

        return $this->render('panier/front.html.twig', [
            'panier' => $panier,
        ]);
    }

    #[Route('/remove-product-from-basket/{idPanier}/{refProduit}', name: 'remove_product_from_basket', methods: ['GET', 'POST'])]
    public function removeProductFromBasket(EntityManagerInterface $entityManager, Request $request, string $refProduit, int $idPanier): Response
    {
        // Get the basket
        $panier = $this->getDoctrine()
            ->getRepository(Panier::class)
            ->find($idPanier);
    
        // Get the product to remove from the basket
        $produit = $this->getDoctrine()
            ->getRepository(Produit::class)
            ->find($refProduit);
    
        // Remove the product from the basket
        if ($panier->getRefProduit()->contains($produit)) {
            $panier->removeRefProduit($produit);
    
            $entityManager->persist($panier);
            $entityManager->flush();
    
            $this->addFlash('success', 'The product has been removed from your basket.');
        } else {
            $this->addFlash('error', 'The product was not found in your basket.');
        }
    
        // Redirect to the basket page
        return $this->redirectToRoute('app_panier_show_front', ['idPanier' => $idPanier]);
    }
    #[Route('/add-product-from-basket/{idPanier}/{refProduit}', name: 'add_product_from_basket', methods: ['GET', 'POST'])]
    public function addProductFromBasket(EntityManagerInterface $entityManager, Request $request, string $refProduit, int $idPanier): Response
    {
      
    
        // Get the product to remove from the basket
        $produit = $this->getDoctrine()
            ->getRepository(Produit::class)
            ->find($refProduit);
            $produit->setQuantite($produit->getQuantite()+1);
            
            $entityManager->persist($produit);
            $entityManager->flush();
    
            $this->addFlash('success', 'The product has been updated from your basket.');
     
    
        // Redirect to the basket page
        return $this->redirectToRoute('app_panier_show_front', ['idPanier' => $idPanier]);
    }
    #[Route('/decrease-product-from-basket/{idPanier}/{refProduit}', name: 'decrease_product_from_basket', methods: ['GET', 'POST'])]
    public function decreaseProductFromBasket(EntityManagerInterface $entityManager, Request $request, string $refProduit, int $idPanier): Response
    {
      
    
        // Get the product to remove from the basket
        $produit = $this->getDoctrine()
            ->getRepository(Produit::class)
            ->find($refProduit);
            $produit->setQuantite($produit->getQuantite()-1);
            
            $entityManager->persist($produit);
            $entityManager->flush();
    
            $this->addFlash('success', 'The product has been updated from your basket.');
     
    
        // Redirect to the basket page
        return $this->redirectToRoute('app_panier_show_front', ['idPanier' => $idPanier]);
    }
    #[Route('/add-product-to-basket/{idPanier}/{refProduit}', name: 'add_product_to_basket', methods: ['GET', 'POST'])]
    public function addProducttoBasket(EntityManagerInterface $entityManager,CommentaireRepository $commentaireRepository ,Request $request, string $refProduit, int $idPanier): Response
    {
       // Get the basket
       $panier = $this->getDoctrine()
       ->getRepository(Panier::class)
       ->find($idPanier);

        
        // Get the product to remove from the basket
        $produit = $this->getDoctrine()
            ->getRepository(Produit::class)
            ->find($refProduit);
            
               // Remove the product from the basket
        if ($panier->getRefProduit()->contains($produit)) {
            $this->addFlash('error', 'le produit deja existe dans votre panier');
    
            
    
            
        } else {
            $produit->setQuantite($produit->getQuantite()+1);
            
            $entityManager->persist($produit);
            $entityManager->flush();
            $panier->addRefProduit($produit);
            $entityManager->persist($panier);
            $entityManager->flush();
            $this->addFlash('success', 'le produit a été ajouté avec succés.');
        }
           
        $commentaires = $commentaireRepository->findByProduit2($produit);
    
        // Redirect to the basket page
        return $this->redirectToRoute('produit_details', ['ref_produit'=> $produit->getRef_produit(),]);
    }
   
    #[Route('/verifypromo/{idPanier}', name: 'verifypromo', methods: ['GET', 'POST'])]

    public function codepromo( Request $req, PromotionController $promotionController , Request $request, EntityManagerInterface $entityManager, int $idPanier): Response
    {       
        $promoValue = $req->get('promo');
        // Dump the promo code to the console
        

        // Get the basket
       $panier = $this->getDoctrine()
       ->getRepository(Panier::class)
       ->find($idPanier);
       
       $taux_promo = $promotionController->verifierCodePromo($promoValue, $panier->getIdUser());
       dump($taux_promo);
              $nouveautotal = $panier->getTotalPanier() * (1 - $taux_promo);
       $panier->setTotalPanier($nouveautotal);
       $entityManager->persist($panier);
       $entityManager->flush();
       return $this->redirectToRoute('app_panier_show_front', ['idPanier'=> $panier->getIdUser()]);
    }
}