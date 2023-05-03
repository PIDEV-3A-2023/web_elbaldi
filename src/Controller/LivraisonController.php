<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Form\LivraisonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/livraison')]
class LivraisonController extends AbstractController
{
    #[Route('/', name: 'app_livraison_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $livraisons = $entityManager
            ->getRepository(Livraison::class)
            ->findAll();

        return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraisons,
        ]);
    }

    #[Route('/new', name: 'app_livraison_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livraison = new Livraison();
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livraison);
            $entityManager->flush();

            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livraison/new.html.twig', [
            'livraison' => $livraison,
            'form' => $form,
        ]);
    }

    #[Route('/{idLivraison}', name: 'app_livraison_show', methods: ['GET'])]
    public function show(Livraison $livraison): Response
    {
        return $this->render('livraison/show.html.twig', [
            'livraison' => $livraison,
        ]);
    }

    #[Route('/{idLivraison}/edit', name: 'app_livraison_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livraison $livraison, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
              //SMS
            if($livraison->getStatusLivraison()=='en expédition'){
            $phoneNumber = $livraison->getIdCmd()->getIdPanier()->getIdUser()->getNumtel();

            $nomComplet = $livraison->getIdCmd()->getIdPanier()->getIdUser()->getNom() . ' ' . $livraison->getIdCmd()->getIdPanier()->getIdUser()->getPrenom();
           
                // Initialize the Twilio client
                $sid = 'AC21dc5d3f63665f41ea25ef019b493cea';
                $token = '6f88f614876498059fc2824ca663f27a';
                //$twilioNumber = 'YOUR_TWILIO_PHONE_NUMBER';
                $clientt = new \Twilio\Rest\Client($sid, $token);

                // Render the SMS body using the Twig template
                $msgg = $this->renderView('sms/livraison.html.twig', [
                    
                    'nom' => $nomComplet,
                    'idlivr'=>$livraison->getIdLivraison(),
                    'idcmd' => $livraison->getIdCmd()->getIdCmd(),
                    'datecmd' => $livraison->getIdCmd()->getDateCmd()->format('d/m/Y'),
                    'adresse'=>$livraison->getAdresseLivraison(),
                    'total' => $livraison->getIdCmd()->getTotal()
                ]);
                // Remove HTML tags from the message body
                $msgg = strip_tags($msgg);

                // Send the SMS
                $msg = $clientt->messages->create(
                    //'+216'.$phoneNumber,
                    '+21690025123',
                    array(
                        'from' => '+12764962871',
                        'body' => 'Votre collier est en route!' . $msgg
                    )
                );
            }
           



            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livraison/edit.html.twig', [
            'livraison' => $livraison,
            'form' => $form,
        ]);
    }

    #[Route('/{idLivraison}', name: 'app_livraison_delete', methods: ['POST'])]
    public function delete(Request $request, Livraison $livraison, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livraison->getIdLivraison(), $request->request->get('_token'))) {
            $entityManager->remove($livraison);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
    }
}
