<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Panier;
use App\Entity\Commande;
use App\Entity\Livraison;
use App\Form\CommandeType;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;
use App\Repository\CommandeRepository;
use App\Repository\LivraisonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $commandes = $entityManager
            ->getRepository(Commande::class)
            ->findAll();

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }
    //MOBILE PART//
    #[Route("/Allcommandes", name: "list")]
    //* Dans cette fonction, nous utilisons les services NormlizeInterface et StudentRepository, 
    //* avec la méthode d'injection de dépendances.
    public function getcommandes(CommandeRepository $repo, SerializerInterface $serializer)
    {
        $commandes = $repo->findAll();
        //* Nous utilisons la fonction normalize qui transforme le tableau d'objets 
        //* students en  tableau associatif simple.
        // $studentsNormalises = $normalizer->normalize($students, 'json', ['groups' => "students"]);

        // //* Nous utilisons la fonction json_encode pour transformer un tableau associatif en format JSON
        // $json = json_encode($studentsNormalises);

        $json = $serializer->serialize($commandes, 'json', ['groups' => "commandes"]);

        //* Nous renvoyons une réponse Http qui prend en paramètre un tableau en format JSON
        return new Response($json);
    }
    //MOBILE PART//

    #[Route("/commande/{id}", name: "commandejson")]
    public function StudentId($id, NormalizerInterface $normalizer, CommandeRepository $repo)
    {
        $student = $repo->find($id);
        $commandeNormalises = $normalizer->normalize($student, 'json', ['groups' => "commandes"]);
        return new Response(json_encode($commandeNormalises));
    }
    #[Route("/addcommandeJSON", name: "addcommandeJSON")]
    public function addStudentJSON(Request $req, NormalizerInterface $Normalizer, EntityManagerInterface $entityManager)
    {

        $em = $this->getDoctrine()->getManager();
        $commande = new commande();
        $commande->setEtat($req->get('etat'));
        $dateString = $req->get('DateCmd');
        $dateCmd = new \DateTime($dateString);
        $commande->setDateCmd($dateCmd);

        $commande->setTotal($req->get('total'));

        $panier = $entityManager->getRepository(Panier::class)->find(intval($req->get('IdPanier')));
        $total = $panier->getTotalPanier();

        $commande->setIdPanier($panier);
        $commande->setAdresse($req->get('adresse'));

        $em->persist($commande);
        $em->flush();
        $client = $panier->getIdUser();
        $nomComplet = $client->getNom() . ' ' . $client->getPrenom();

        $dompdf = new Dompdf();
        $options = new Options();
        $options->setIsRemoteEnabled(true);

        $dompdf->setOptions($options);




        $html = $this->renderView('pdf/facture.html.twig', [

            'items' => $panier->getRefProduit(),
            'total' => $total,
            'tot' => $panier->getTotalPanier(),
            'nbarticles' => $panier->getNombreArticle(),
            'nom' => $nomComplet,
            'Datecmd' => $commande->getDateCmd()->format('d/m/Y'),
            'adresse' => $commande->getAdresse()
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Get the PDF binary data
        $pdfData = $dompdf->output();

        // Save the PDF to a file on the server
        $filename = 'commande' . $commande->getIdCmd() . '.pdf';
        $file = 'D:\DownloadsD\\' . $filename;
        file_put_contents($file, $pdfData);


        // Envoi d'un email à chaque utilisateur


        $ms = new GmailSmtpTransport('elbaldinotification@gmail.com', 'eymmlmaxtvwotrzo');
        $mailer = new Mailer($ms);
        $emailBody = $this->renderView('email/newcmd.html.twig', [
            'nom' => $client->getNom(),
            'prenom' => $client->getPrenom(),
            'items' => $panier->getRefProduit(),
            'total' => $total
        ]);
        $message = (new TemplatedEmail())
            ->from('elbaldinotification@gmail.com')
            ->to($client->getEmail())
            ->subject('MERCI POUR VOTRE COMMANDE !')
            ->html($emailBody)
            ->attachFromPath($file, $filename, 'application/pdf');





        $mailer->send($message);

        $jsonContent = $Normalizer->normalize($commande, 'json', ['groups' => 'commandes']);
        return new Response(json_encode($jsonContent));
    }

    #[Route("/deletecommandeJSON/{id}", name: "deletecommandeJSON")]
    public function deletecommandeJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository(Commande::class)->find($id);
        $em->remove($commande);
        $em->flush();
        $jsonContent = $Normalizer->normalize($commande, 'json', ['groups' => 'commandes']);
        return new Response("commande deleted successfully " . json_encode($jsonContent));
    }
    #[Route('/new/{idPanier}', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $idPanier, CommandeRepository $commandeRepository, LoggerInterface $logger): Response
    {
        $panier = $entityManager->getRepository(Panier::class)->find($idPanier);

        if (!$panier) {
            throw $this->createNotFoundException('The Panier does not exist');
        }

        $commande = new Commande();
        $commande->setIdPanier($panier);
        $orderCount = $commandeRepository->countByCommande($panier->getIdPanier());
        $total = $panier->getTotalPanier();
        $logger->info(sprintf('total before : %d', $total));
        $logger->info(sprintf('Order count: %d', $orderCount));
        if ($orderCount >= 5) {
            $total *= 0.9;
        }
        $commande->setTotal($total);
        $logger->info(sprintf('total after : %d', $total));
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();
            $client = $panier->getIdUser();
            $nomComplet = $client->getNom() . ' ' . $client->getPrenom();
            $imagePath = 'asset(http://localhost/images/elbaldi.jpg)';

            //pdf        
            // Créer le PDF avec Dompdf
// Create the PDF with Dompdf
            $dompdf = new Dompdf();
            $options = new Options();
            $options->setIsRemoteEnabled(true);

            $dompdf->setOptions($options);

            if ($orderCount >= 5) {
                $pdftotal = $panier->getTotalPanier() . 'DT - 10% de fidélité = ' . $total . 'DT ';
            } else {
                $pdftotal = $total;
            }
            $html = $this->renderView('pdf/facture.html.twig', [

                'items' => $panier->getRefProduit(),
                'total' => $pdftotal,
                'tot' => $panier->getTotalPanier(),
                'nbarticles' => $panier->getNombreArticle(),
                'nom' => $nomComplet,
                'Datecmd' => $commande->getDateCmd()->format('d/m/Y'),
                'adresse' => $commande->getAdresse()
            ]);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            // Get the PDF binary data
            $pdfData = $dompdf->output();

            // Save the PDF to a file on the server
            $filename = 'commande' . $commande->getIdCmd() . '.pdf';
            $file = 'D:\DownloadsD\\' . $filename;
            file_put_contents($file, $pdfData);


            // Envoi d'un email à chaque utilisateur


            $ms = new GmailSmtpTransport('elbaldinotification@gmail.com', 'eymmlmaxtvwotrzo');
            $mailer = new Mailer($ms);
            $emailBody = $this->renderView('email/newcmd.html.twig', [
                'nom' => $client->getNom(),
                'prenom' => $client->getPrenom(),
                'items' => $panier->getRefProduit(),
                'total' => $pdftotal
            ]);
            $message = (new TemplatedEmail())
                ->from('elbaldinotification@gmail.com')
                ->to($client->getEmail())
                ->subject('MERCI POUR VOTRE COMMANDE !')
                ->html($emailBody)
                ->attachFromPath($file, $filename, 'application/pdf');





            $mailer->send($message);

            return $this->redirectToRoute('app_commande_thankyou', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
            'orderCount' => $orderCount
        ]);
    }


    #[Route('/thankyou', name: 'app_commande_thankyou')]
    public function thankyou(): Response
    {
        return $this->render('commande/thankyou.html.twig');
    }


    #[Route('/{idCmd}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{idCmd}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{idCmd}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commande->getIdCmd(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }



}