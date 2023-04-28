<?php

namespace App\Controller;

use App\Entity\Bonplan;
use App\Repository\BonplanRepository;
use App\Form\BonplanType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\StringLoaderExtension;
use MercurySeries\FlashyBundle\FlashyNotifier;


#[Route('/bonplan')]
class BonplanController extends AbstractController
{
    #[Route('/back', name: 'app_bonplan_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $bonplans = $entityManager
            ->getRepository(Bonplan::class)
            ->findAll();

        return $this->render('bonplan/index.html.twig', [
            'bonplans' => $bonplans,
        ]);
    }
    
    #[Route('/searchbonplanajax', name: 'ajaxbonplan', methods: ['GET'])]
    public function searchajax(Request $request, BonplanRepository $repository)
    {
        $requestString=$request->get('searchValue');
        $bonplans = $repository->findBonplan($requestString);

        $averageNotes = [];

        foreach ($bonplans as $bonplan) {
            $averageNote = $repository->findAverageNoteAvisByIdBonplan($bonplan->getIdBonplan());
            $averageNotes[$bonplan->getIdBonplan()] = $averageNote;
        }
    
        return $this->render('bonplan/ajax.html.twig', [
            "bonplans"=>$bonplans,
            'averageNotes' => $averageNotes,
        ]);
    }

   #[Route('/front', name: 'app_bonplan_indexFront', methods: ['GET'])]
    public function indexFront(EntityManagerInterface $entityManager,BonplanRepository $repository ): Response
    {
        
        $bonplans = $repository->findAll();
    $averageNotes = [];

    foreach ($bonplans as $bonplan) {
        $averageNote = $repository->findAverageNoteAvisByIdBonplan($bonplan->getIdBonplan());
        $averageNotes[$bonplan->getIdBonplan()] = $averageNote;
    }

        

        return $this->render('bonplan/indexFront.html.twig', [
            'bonplans' => $bonplans,
            'averageNotes' => $averageNotes,
        ]);
    }

    #[Route('/newback', name: 'app_bonplan_new', methods: ['GET', 'POST'])]
    public function new(Request $request,SluggerInterface $slugger,EntityManagerInterface $entityManager, FlashyNotifier $flashy ): Response
    {
        $bonplan = new Bonplan();
        $form = $this->createForm(BonplanType::class, $bonplan);
        $form->handleRequest($request);

       /* if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('imageBonplan')->getData();
        if ($file) {
            $fileName = uniqid().'.'.$file->guessExtension();
          
           // $fileData = file_get_contents($file);
            $file->move(
                'C:\xampp\htdocs\images',
                $fileName
            );
            //$binaryData = stream_get_contents($fileData);
            $bonplan->setImageBonplan($fileName);
        }
            $entityManager->persist($bonplan);
            $entityManager->flush();
            return $this->redirectToRoute('app_bonplan_index', [], Response::HTTP_SEE_OTHER);
        }*/
        if ($form->isSubmitted() && $form->isValid()) {

            $brochureFile = $form->get('imageBonplan')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('post_image'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $bonplan->setImageBonplan($newFilename);
            }
            $entityManager->persist($bonplan);
            $entityManager->flush();
            $this->addFlash('success', 'This Bon Plan added successfully');


            return $this->redirectToRoute('app_bonplan_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('bonplan/new.html.twig', [
            'bonplan' => $bonplan,
            'form' => $form,
        ]);
    }

    #[Route('/newfront', name: 'app_bonplan_newfront', methods: ['GET', 'POST'])]
    public function newfront(Request $request,SluggerInterface $slugger,EntityManagerInterface $entityManager): Response
    {
        $bonplan = new Bonplan();
        $form = $this->createForm(BonplanType::class, $bonplan);
        $form->handleRequest($request);

       /* if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('imageBonplan')->getData();
        if ($file) {
            $fileName = uniqid().'.'.$file->guessExtension();
          
           // $fileData = file_get_contents($file);
            $file->move(
                'C:\xampp\htdocs\images',
                $fileName
            );
            //$binaryData = stream_get_contents($fileData);
            $bonplan->setImageBonplan($fileName);
        }
            $entityManager->persist($bonplan);
            $entityManager->flush();
            return $this->redirectToRoute('app_bonplan_index', [], Response::HTTP_SEE_OTHER);
        }*/
        if ($form->isSubmitted() && $form->isValid()) {

            $brochureFile = $form->get('imageBonplan')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('post_image'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $bonplan->setImageBonplan($newFilename);
            }
            $entityManager->persist($bonplan);
            $entityManager->flush();

            return $this->redirectToRoute('app_bonplan_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('bonplan/new.html.twig', [
            'bonplan' => $bonplan,
            'form' => $form,
        ]);
    }

    #[Route('/{idBonplan}', name: 'app_bonplan_show', methods: ['GET'])]
    public function show(Bonplan $bonplan): Response
    {
        return $this->render('bonplan/show.html.twig', [
            'bonplan' => $bonplan,
        ]);
    }
    
    #[Route('/showFront/{idBonplan}', name: 'app_bonplanFront_show', methods: ['GET'])]
    public function showFront(Bonplan $bonplan): Response
    {
        return $this->render('bonplan/showFront.html.twig', [
            'bonplan' => $bonplan,
        ]);
    }
     
    
    #[Route('/{idBonplan}/edit', name: 'app_bonplan_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bonplan $bonplan, SluggerInterface $slugger,EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BonplanType::class, $bonplan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('imageBonplan')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('post_image'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $bonplan->setImageBonplan($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_bonplan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bonplan/edit.html.twig', [
            'bonplan' => $bonplan,
            'form' => $form,
        ]);
    }

    #[Route('/{idBonplan}', name: 'app_bonplan_delete', methods: ['POST'])]
    public function delete(Request $request, Bonplan $bonplan, EntityManagerInterface $entityManager, FlashyNotifier $flashy
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bonplan->getIdBonplan(), $request->request->get('_token'))) {
            $entityManager->remove($bonplan);
            $entityManager->flush();
            $this->addFlash('danger', 'This reclamation deleted successfully');

        }

        return $this->redirectToRoute('app_bonplan_index', [], Response::HTTP_SEE_OTHER);
    }


    public function listAvis(Bonplan $bonlan)
{
    $avisRepository = $this->getDoctrine()->getRepository(Avis::class);
    $query = $avisRepository->createQueryBuilder('a')
        ->where('a.idBonplan = :id')
        ->setParameter('id', $id)
        ->groupBy('a.id_bonplan')
        ->getQuery();
    $avis = $query->getResult();



    return $this->render('my_template.html.twig', [
        'avis' => $avis,
    ]);
}



   
}