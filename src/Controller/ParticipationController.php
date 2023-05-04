<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Client;
use App\Entity\Participation;
use App\Form\ParticipationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Font\NotoSans;
use Knp\Component\Pager\PaginatorInterface;
#[Route('/participation')]
class ParticipationController extends AbstractController
{
    #[Route('/', name: 'app_participation_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,Request $request,PaginatorInterface $paginator): Response
    {
        $participations = $entityManager
            ->getRepository(Participation::class)
            ->findAll();
     
            $participations =  $paginator->paginate(
                $participations, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                5 /*limit per page*/
            );
        return $this->render('participation/index.html.twig', [
            'participations' => $participations,
        ]);
    }

    #[Route('/new', name: 'app_participation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $participation = new Participation();
        $form = $this->createForm(ParticipationType::class, $participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($participation);
            $entityManager->flush();

            return $this->redirectToRoute('app_participation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('partipation/new.html.twig', [
            'participation' => $participation,
            'form' => $form,
        ]);
    }
   public function qrcoding($data)
   {$writer = new PngWriter();
    $qrCode = QrCode::create($data)
        ->setEncoding(new Encoding('UTF-8'))
        ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
        ->setSize(500)
        ->setMargin(0)
        ->setForegroundColor(new Color(0, 0, 0))
        ->setBackgroundColor(new Color(255, 255, 255));
    $logo = Logo::create('front/images/R2.png')
        ->setResizeToWidth(90);
    $label = Label::create('')->setFont(new NotoSans(8));

    $qrCodes = [];
    $qrCodes['img'] = $writer->write($qrCode, $logo)->getDataUri();
    $qrCodes['simple'] = $writer->write(
                            $qrCode,
                            null,
                            $label->setText('Simple')
                        )->getDataUri();

    $qrCode->setForegroundColor(new Color(255, 0, 0));
    $qrCodes['changeColor'] = $writer->write(
        $qrCode,
        null,
        $label->setText('Color Change')
    )->getDataUri();

    $qrCode->setForegroundColor(new Color(0, 0, 0))->setBackgroundColor(new Color(255, 0, 0));
    $qrCodes['changeBgColor'] = $writer->write(
        $qrCode,
        null,
        $label->setText('Background Color Change')
    )->getDataUri();

    $qrCode->setSize(200)->setForegroundColor(new Color(0, 0, 0))->setBackgroundColor(new Color(255, 255, 255));
    $qrCodes['withImage'] = $writer->write(
        $qrCode,
        $logo,
        $label->setText('Reused')->setFont(new NotoSans(20))
    )->getDataUri();
    return $qrCodes;
}
    #[Route('/{idEvent}/{idClient}', name: 'app_participation_new1', methods: ['GET', 'POST'])]
    public function showSingle(Client $client,Request $request, EntityManagerInterface $entityManager,Event $event): Response
    {    $participation = new Participation();
        
        $form = $this->createForm(ParticipationType::class, $participation, [
            'event' => $event,
            'client'=> $client,
        ]);
     

        
        $qrImageData = $this->qrcoding('http://127.0.0.1:8000/participation/bot/' . $client->getIdClient() . '/' . $event->getIdEvent());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($participation);
            $entityManager->flush();

            return $this->redirectToRoute('app_participation_index', [], Response::HTTP_SEE_OTHER);
           
        }
        return $this->renderForm('event/showSingle.html.twig', [
            'participation' => $participation,
            'form' => $form,
            'event' => $event,
            'client' =>$client,
            'qr_code'=> $qrImageData,
            
        ]);
    }
    #[Route('/{idParticipation}', name: 'app_participation_show', methods: ['GET'])]
    public function show(Participation $participation): Response
    {
        return $this->render('participation/show.html.twig', [
            'participation' => $participation,
        ]);
    }
    #[Route('ticket/{idParticipation}/{idEvent}', name: 'app_participation_ticket', methods: ['GET'])]
    public function showTicket(Participation $participation,Event $event): Response
    {$qrImageData = $this->qrcoding('Nom Evenement :'.$event->getTitleEvent()."*
     ***"."Nom :".$participation->getNomClient()."***   
    **"."Prenom :".$participation->getPrenomClient() );
        return $this->render('participation/ticket.html.twig', [
            'participation' => $participation,
            'event' => $event,
            'qr_code'=> $qrImageData,
        ]);
    }

    
    #[Route('/bot/{idClient}/{idEvent}', name: 'app_participation_redirect', methods: ['GET', 'POST'])]
     public function redirectToform(Client $client,Request $request, EntityManagerInterface $entityManager,Event $event): Response
{    $participation = new Participation();
    
    $form = $this->createForm(ParticipationType::class, $participation, [
        'event' => $event,
        'client'=> $client,
    ]);
 
   
    
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($participation);
        $entityManager->flush();
     
        return  $this->redirectToRoute('app_participation_ticket', [
            'idParticipation' => $participation->getIdParticipation(),
            'idEvent' => $event->getIdEvent()
        ], Response::HTTP_SEE_OTHER);
       
    }
    return $this->renderForm('event/redirect.html.twig', [
        'participation' => $participation,
        'form' => $form,
        'event' => $event,
        'client' =>$client,
        
        
    ]);
}
    #[Route('/{idParticipation}/edit', name: 'app_participation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Participation $participation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ParticipationType::class, $participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_participation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participation/edit.html.twig', [
            'participation' => $participation,
            'form' => $form,
        ]);
    }
   
    #[Route('/{idParticipation}', name: 'app_participation_delete', methods: ['POST'])]
    public function delete(Request $request, Participation $participation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participation->getIdParticipation(), $request->request->get('_token'))) {
            $entityManager->remove($participation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_participation_index', [], Response::HTTP_SEE_OTHER);
    }

}

    


