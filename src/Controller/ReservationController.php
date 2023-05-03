<?php

namespace App\Controller;
use App\Entity\Reservation;
use App\Entity\Bonplan;
use App\Entity\Utilisateur;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;


#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reservations = $entityManager
            ->getRepository(Reservation::class)
            ->findAll();

        $rdvs = [];
        foreach ($reservations as $event)
        {
            $rdvs[]=[
                'title'=>$event->getIdUser()->getNom() . ' ' . $event->getIdUser()->getPrenom(),
                'start'=>$event->getDateReservation()->format("Y-m-d"),
                'end'=>$event->getDateReservation()->modify("+1 day")->format("Y-m-d"),
                'backgroundColor'=> '#0ec51',
                'borderColor'=> 'green',
                'textColor' => 'black'
            ];
        }
        $data = json_encode($rdvs);

        return $this->render('reservation/index.html.twig' ,compact('data', 'reservations' ));
    }

   
    

    #[Route('/new/{idBonplan}', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $bonplanId = $request->attributes->get('idBonplan');
        $bonplan = $entityManager->getRepository(Bonplan::class)->find($bonplanId);
        //$bonplanTitre = $bonplan->getTitreBonplan();
        $reservation->setIdBonplan($bonplan);
        $form = $this->createForm(ReservationType::class, $reservation,
        [
            'idBonplan' => $bonplan
                ]);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérification de la date de réservation
            $date = $reservation->getDateReservation();
            $now = new \DateTime();
            if ($date < $now) {
                // Si la date est antérieure à la date actuelle, renvoyer une erreur
                $form->addError(new FormError("Message d'erreur :La date de réservation ne peut pas être antérieure à la date actuelle."));
                return $this->renderForm('reservation/new.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form,
                ]);
            }
            $entityManager->persist($reservation);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_bonplan_indexFront', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }
    

    #[Route('/{idReservation}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{idReservation}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérification de la date de réservation
            $date = $reservation->getDateReservation();
            $now = new \DateTime();
            if ($date < $now) {
                // Si la date est antérieure à la date actuelle, renvoyer une erreur
                $form->addError(new FormError("Message d'erreur :La date de réservation ne peut pas être inférieure à la date actuelle."));
                return $this->renderForm('reservation/edit.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form,
                ]);
            }
            $entityManager->flush();
    
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }
    

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{idReservation}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getIdReservation(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
