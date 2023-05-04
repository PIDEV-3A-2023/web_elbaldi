<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Event;
use App\Form\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Participation;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Component\Mailer\Mailer;



#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $events = $entityManager
            ->getRepository(Event::class)
            ->findAll();

        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }


    public function CountEvent(EntityManagerInterface $entityManager, string $t)
    {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('COUNT(p)')
            ->from(Participation::class, 'p')
            ->where('p.idEvenement=:participation')
            ->setParameter('participation', $t);



        $query = $qb->getQuery();
        $results = $query->getSingleScalarResult();
        return $results;

    }

    #[Route('/index2', name: 'app_event_front', methods: ['GET'])]
    public function index2(EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {
        $events = $entityManager
            ->getRepository(Event::class)
            ->findBy(array(), array('timeEvent' => 'ASC'));
        $client = $entityManager
            ->getRepository(Client::class)
            ->find(1);
        if (!$client) {
            throw $this->createNotFoundException(
                'No product found for id '
            );
        }

        $results = array();
        for ($i = 0, $size = count($events); $i < $size; ++$i) {
            $results[$i] = $this->CountEvent($entityManager, $events[$i]->getIdEvent());
        }



        $events = $paginator->paginate(
            $events, /* query NOT result */
            $request->query->getInt('page', 1),
            /*page number*/
            4 /*limit per page*/
        );
        $results = $paginator->paginate(
            $results, /* query NOT result */
            $request->query->getInt('page', 1),
            /*page number*/
            4 /*limit per page*/
        );



        return $this->render('event/showFront.html.twig', [
            'events' => $events,
            'client' => $client,
            'results' => $results,

        ]);
    }
    #[Route('/Roue/{idClient}', name: 'app_event_roue', methods: ['GET', 'POST'])]
    public function roue(Request $request, Client $client,  EntityManagerInterface $entityManager, $idClient): Response
    {
        $client = $entityManager->getRepository(Client::class)->find($idClient);
        $value = $request->query->get('value');
        $value = 9;
        switch ($value) {
            case 1:

                $client->setPoints($client->getPoints() + 500);
                $entityManager
                    ->flush();
                break;
            case 2:

                $client->setPoints($client->getPoints() + 200);
                $entityManager
                    ->flush();
                break;
            case 6:
                $client->setPoints($client->getPoints() + 2000);
                $entityManager
                    ->flush();
                break;
            case 7:
                $client->setPoints($client->getPoints() + 1000);
                $entityManager
                    ->flush();
                break;
            case 13:

                $client->setPoints($client->getPoints() + 5000);
                $entityManager
                    ->flush();
                break;
            case 9:
                $code = rand(1000000, 9999999);
                $emailBody = $this->renderView('event/mailevent.html.twig', [
                    'expiration_
        ' => new \DateTime('+7 days'),
                        'username' => $client->getNom(),
                        'code' => $code,
                ]);
               
                $email = 
                (new TemplatedEmail())
                    ->from('elbaldinotification@gmail.com')
                    ->to('skander.maamouri@esprit.tn')
                    ->subject('You have won!')

                    ->html($emailBody);
                try {
                    $ms = new GmailSmtpTransport('elbaldinotification@gmail.com', 'eymmlmaxtvwotrzo');
        $mailer = new Mailer($ms);
                    $mailer->send($email);
                } catch (TransportExceptionInterface $e) {
                    echo ("error");
                    // some error prevented the email sending; display an
                    // error message or try to resend the message
                }
                break;

        }
        return $this->render('event/roue.html.twig');
    }
    #[Route('/new', name: 'app_event_new1', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('imageEvent')->getData();
            $fichier = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move(
                $this->getParameter('images_directory'),
                $fichier
            );
            $event->setimageEvent($fichier);
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{idEvent}', name: 'app_event_showSingle', methods: ['GET'])]
    public function showSingle(Event $event): Response
    {
        return $this->render('event/showSingle.html.twig', [
            'event' => $event,
        ]);
    }


    #[Route('/{idEvent}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/showSingle.html.twig', [
            'event' => $event,
        ]);
    }
    #[Route('/show1', name: 'app_event_show1', methods: ['GET'])]
    public function show1(Event $event): Response
    {

        return $this->render('event/showFront.html.twig', [
            'event' => $event,

        ]);
    }


    #[Route('/{idEvent}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('imageEvent')->getData();
            $fichier = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move(
                $this->getParameter('images_directory'),
                $fichier
            );
            $event->setimageEvent($fichier);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{idEvent}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $event->getIdEvent(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }


}