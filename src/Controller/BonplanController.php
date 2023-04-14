<?php

namespace App\Controller;

use App\Entity\Bonplan;
use App\Form\BonplanType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
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
    
   #[Route('/front', name: 'app_bonplan_indexFront', methods: ['GET'])]
    public function indexFront(EntityManagerInterface $entityManager): Response
    {
        $bonplans = $entityManager
            ->getRepository(Bonplan::class)
            ->findAll();

        return $this->render('bonplan/indexFront.html.twig', [
            'bonplans' => $bonplans,
        ]);
    }

    #[Route('/newback', name: 'app_bonplan_new', methods: ['GET', 'POST'])]
    public function new(Request $request,SluggerInterface $slugger,EntityManagerInterface $entityManager): Response
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
    public function delete(Request $request, Bonplan $bonplan, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bonplan->getIdBonplan(), $request->request->get('_token'))) {
            $entityManager->remove($bonplan);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_bonplan_index', [], Response::HTTP_SEE_OTHER);
    }
}
