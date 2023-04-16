<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Produit;
use App\Entity\Utilisateur;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commentaire')]
class CommentaireController extends AbstractController
{
    #[Route('/', name: 'app_commentaire_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $commentaires = $entityManager
            ->getRepository(Commentaire::class)
            ->findAll();

        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaires,
        ]);
    }

    #[Route('/new', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id_commentaire}', name: 'app_commentaire_show', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/{id_commentaire}/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id_commentaire}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId_commentaire(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/produit/{ref_produit}/add-comment', name:'add_comment', methods: ['Get','POST'])]
    public function addComment(Request $request, EntityManagerInterface $entityManager, Produit $produit,CommentaireRepository $commentaireRepository,UtilisateurRepository $utilisateurRepository): Response
    {
        $commentaires = $commentaireRepository->findByProduit2($produit);
        $user = $utilisateurRepository->find(2510);
        $commentaire = new Commentaire();
        //$commentaire->setUser($this->getUser());
  
        $commentaire->setUser($user);
        $commentaire->setProduit($produit);
        //$commentaire->setContenu($request->request->get('contenu'));
        $contenu = $request->request->get('contenu');
        if ($contenu === null) {
            throw new \InvalidArgumentException('Contenu cannot be null.');
        }
        $commentaire->setContenu($contenu);
        $commentaire->setDateComm(new \DateTime());

        $entityManager->persist($commentaire);
        $entityManager->flush();
        return $this->redirectToRoute('produit_details', ['ref_produit' => $produit->getRef_produit()]);

    }

    /**
 * @Route("/produit/{ref_produit}/commentaires", name="produit_commentaires")
 */
public function commentaires(Produit $produit)
{
    $commentaires = $produit->getCommentaires();

    return $this->render('produitFront/produitDetails.html.twig', [
        'produit' => $produit,
        'commentaires' => $commentaires,
    ]);
}


}
