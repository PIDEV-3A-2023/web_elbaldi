<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\FileUploader;

class ProduitController extends AbstractController
{
    /**
     * @Route("/admin/produit", name="app_produit_index", methods={"GET"})
     */
    public function index(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
        ]);
    }
     /**
     * @Route("/client/produit", name="app_produit_indexFront", methods={"GET"})
     */
    public function indexFront(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();

        return $this->render('produitFront/indexFront.html.twig', [
            'produits' => $produits,
        ]);
    }

    /**
     * @Route("/admin/produit/new", name="app_produit_new", methods={"GET","POST"})
     */
    public function new(Request $request,FileUploader $fileUploader): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            // Upload new image if provided
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            $newFilename = $fileUploader->upload($imageFile);
            $produit->setImage($newFilename);}
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index');
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/produit/show{ref_produit}", name="app_produit_show", methods={"GET"})
     */
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

   /**
 * @Route("/{ref_produit}/edit", name="app_produit_edit", methods={"GET","POST"})
 */
public function edit(Request $request, Produit $produit, FileUploader $fileUploader): Response
{
    $form = $this->createForm(ProduitType::class, $produit);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Upload new image if provided
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            $newFilename = $fileUploader->upload($imageFile);
            $produit->setImage($newFilename);
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('app_produit_index');
    }

    return $this->render('produit/edit.html.twig', [
        'produit' => $produit,
        'form' => $form->createView(),
    ]);
}

#[Route('/deleteProduit/{ref_produit}', name: 'app_produit_delete')]
public function delete($ref_produit,ManagerRegistry $doctrine): Response
{ //trouver le bon produit 
$repoC=$doctrine->getRepository(Produit::class);
$produit=$repoC->find($ref_produit);
//utiliser manager pour supprimer le produit trouve
$em=$doctrine->getManager();
$em->remove($produit);
$em->flush();
return $this->redirectToRoute('app_produit_index');
}

}