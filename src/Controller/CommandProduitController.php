<?php

namespace App\Controller;

use App\Entity\CommandProduit;
use App\Form\CommandProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/command/produit')]
class CommandProduitController extends AbstractController
{
    #[Route('/', name: 'app_command_produit_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $commandProduits = $entityManager
            ->getRepository(CommandProduit::class)
            ->findAll();

        return $this->render('command_produit/index.html.twig', [
            'command_produits' => $commandProduits,
        ]);
    }

    #[Route('/new', name: 'app_command_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commandProduit = new CommandProduit();
        $form = $this->createForm(CommandProduitType::class, $commandProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commandProduit);
            $entityManager->flush();

            return $this->redirectToRoute('app_command_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('command_produit/new.html.twig', [
            'command_produit' => $commandProduit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_command_produit_show', methods: ['GET'])]
    public function show(CommandProduit $commandProduit): Response
    {
        return $this->render('command_produit/show.html.twig', [
            'command_produit' => $commandProduit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_command_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CommandProduit $commandProduit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandProduitType::class, $commandProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_command_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('command_produit/edit.html.twig', [
            'command_produit' => $commandProduit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_command_produit_delete', methods: ['POST'])]
    public function delete(Request $request, CommandProduit $commandProduit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commandProduit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commandProduit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_command_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
