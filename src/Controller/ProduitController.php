<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Commentaire;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Repository\CommandeProduitRepository;
use App\Repository\CategorieRepository;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\JsonResponse;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Mailer;



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
    public function indexFront(ProduitRepository $produitRepository, CategorieRepository $categorieRepository, Request $request): Response
    {
        $produits = $produitRepository->findAll();
        $categories = $categorieRepository->findAll();
        return $this->render('produitFront/indexFront.html.twig', [
            'categories' => $categories,
            'produits' => $produits,
        ]);
    }
       /**
     * @Route("/produits/tri/{tri}", name="produits_tri")
     */
    public function trier(ProduitRepository $produitRepository, CategorieRepository $categorieRepository, $tri): Response
    {
        
        switch ($tri) {
            case 'prix_asc':
                $produits = $produitRepository->findByPrixVenteAsc();
                break;
            case 'prix_desc':
                $produits = $produitRepository->findByPrixVenteDesc();
                break;
            default:
                $produits = $produitRepository->findAll();
        }

        $categories = $categorieRepository->findAll();
// on  génére le code HTML correspondant à la vue
        $html = $this->render('produitFront/indexFront.html.twig', [
            'produits' => $produits,
            'categories' => $categories,
            
        ]);
       // retourne une réponse JSON
        return new JsonResponse([
            'success' => true,
            'html' => $html->getContent()
        ]);
        
    }
    /**
     * @Route("/produit/{ref_produit}", name="produit_details", methods={"GET"})
     */
    public function produitDetails(Produit $produit, CommentaireRepository $commentaireRepository): Response
    {
        $commentaires = $commentaireRepository->findByProduit2($produit);
        return $this->render('produitFront/produitDetails.html.twig', [
            'produit' => $produit,
            'commentaires' => $commentaires,
        ]);
    }

    /**
     * @Route("/admin/produit/new", name="app_produit_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader, ProduitRepository $produitRepository): Response
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
                $produit->setImage($newFilename);
            } else {
                $produit->setImage('images/image_par_defaut.png');
            }
            $entityManager->flush();

            $categoryId  = $produit->getCategorie()->getid_categorie();
    
            //Api Email 
            // récupération des emails des utilisateurs
          
            $clients = $produitRepository->findByEmailsByCategoryId($categoryId);

            // Envoi d'un email à chaque utilisateur
            foreach ($clients as $client) {
            
                $ms = new GmailSmtpTransport('elbaldinotification@gmail.com', 'eymmlmaxtvwotrzo'); 
                $mailer = new Mailer($ms);
                $emailBody = $this->renderView('email/nouveau_produit.html.twig', 
                    ['produit' => $produit, 'nom' => $client['nom'], 'prenom' => $client['prenom']]
                );
            $message = (new TemplatedEmail())
                ->from('elbaldinotification@gmail.com')
                ->to($client['email'])
                ->subject('BONNE NOUVELLE !')
                ->html($emailBody);
                
                
                $mailer->send($message);
                }
                
            //SMS

            $phoneNumbers = $produitRepository->findByTelByCategoryId($categoryId);
            foreach ($phoneNumbers as $phoneNumber) {
                // Initialize the Twilio client
                $sid = 'AC71e1feb032fa0923d3b0a48b604bd489';
                $token = 'c8f863d557084602ab043bfb8ac92d76';
                //$twilioNumber = 'YOUR_TWILIO_PHONE_NUMBER';
                $clientt = new \Twilio\Rest\Client($sid, $token);

                // Render the SMS body using the Twig template
                $msgg = $this->renderView('sms/nouveau_produit.html.twig', [
                    'produit' => $produit,
                    // 'nom' => $phoneNumber['nom'],
                    //'prenom' => $phoneNumber['prenom'],
                    'nom' => 'Bahroun',
                    'prenom' => 'Yasmine'
                ]);
                // Remove HTML tags from the message body
                $msgg = strip_tags($msgg);

                // Send the SMS
                $msg = $clientt->messages->create(
                    //'+216'.$phoneNumber,
                    '+21697618378',
                    array(
                        'from' => '+12766378855',
                        'body' => 'BONNE NOUVELLE ! ' . $msgg
                    )
                );
            }


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
/**
 * @Route("/update_prix/{ref_produit}/{taux}", name="app_produit_update_prix", methods={"POST"})
 */
public function updatePrix(Request $request,Produit $produit , ProduitRepository $produitRepository): JsonResponse
{  
    $taux = $request->attributes->get('taux');
    $nouveauPrixVente = $produit->getPrixVente() * (1 - $taux/100);
    $produit->setPrixVente($nouveauPrixVente);
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($produit);
    $entityManager->flush();

    return new JsonResponse(['nouveauPrixVente' => $nouveauPrixVente]);
}

    #[Route('/deleteProduit/{ref_produit}', name: 'app_produit_delete')]
    public function delete($ref_produit, ManagerRegistry $doctrine): Response
    {   
        //trouver le bon produit 
        $repoC = $doctrine->getRepository(Produit::class);
        $produit = $repoC->find($ref_produit);

        // utiliser manager pour supprimer le produit trouvé
        $em = $doctrine->getManager();
        $em->remove($produit);
        $em->flush();

        return $this->redirectToRoute('app_produit_index');
    }

    #[Route('/stat', name: 'app_produit_stats2')]
    public function stats2(Request $request, ProduitRepository $produitRepository, CategorieRepository $categorie, CommandeProduitRepository $commandProduitRepository): Response
    {
        $currentMonthName = (new \DateTime())->format('F');
        $produit = new Produit();
        $topProducts = $commandProduitRepository->top5prod();
        $totalProducts = $produitRepository->countProducts();
        $produit = [];
        $minProducts = $commandProduitRepository->findFiveLeastSoldProducts();
        $produitsMin = [];
        foreach ($minProducts as $minProduct) {
            $produit = $produitRepository->findOneBy(['ref_produit' => $minProduct['ref_produit']]);
            if ($produit) {
                $produitsMin[] = $produit;
            }
        }
        $produit = $produitRepository->getStat();
        $prods = array(array("categorie", "Nombre de demandes "));
        $i = 1;
        foreach ($produit as $prod) {
            $prods[$i] = array($prod["nomCategorie"], $prod["nbre"]);
            $i = $i + 1;
        }

        $pieChart = new Piechart();

        $pieChart->getData()->setArrayToDataTable($prods);
        $pieChart->getOptions()->setTitle('Produits par catégories');
        $pieChart->getOptions()->setHeight(600);
        $pieChart->getOptions()->setWidth(600);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#9a000a');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(25);
        $pieChart->getOptions()->setColors(['#e8cac6', '#c74d4d', '#c78da7','#a43120','#774242','#8B0000']); // Spécifiez les couleurs ici


        return $this->render('statistique.html.twig', [

            'pieChart' => $pieChart,
            'totalProducts' => $totalProducts,
            'topProducts' => $topProducts,
            'produitsMin' => $produitsMin,
            'currentMonthName' => $currentMonthName, // Passer le nom du mois à la vue

        ]);
    }



    /// commentaires




    /**
     * @Route("/produit/{ref_Produit}/add_commentaire", name="app_produit_add_commentaire")
     */
    public function addCommentaire(Request $request, EntityManagerInterface $entityManager, ProduitRepository $produitRepository, $ref_Produit): Response
    {
        $produit = $produitRepository->findOneBy(['ref_Produit' => $ref_Produit]);

        $commentaire = new Commentaire();
        $commentaire->setProduit($produit);

        $form = $this->createFormBuilder($commentaire)
            ->add('commentaire', TextareaType::class, [
                'label' => 'Commentaire'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Ajouter'
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire = $form->getData();
            $commentaire->setUser($this->getUser());
            $commentaire->setDateComm(new \DateTime());

            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_details', ['ref_Produit' => $ref_Produit]);
        }

        return $this->render('produitFront/produitDetails.html.twig', [
            'produit' => $produit,
            'form' => $form->createView()
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
