<?php

namespace App\Controller;

 use App\Entity\Utilisateur;
 use App\Form\AdminAproveType;
 use App\Form\AdminBanType;
 use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function home(): Response
    {
        return $this->redirectToRoute('app_login');
    }

    #[Route('/profile', name: 'profile')]
    public function index(): Response
    {
        if($this->getUser()->getRoles()== ['agent'])
        if($this->getUser()->getEtat()!= ['approve'])
        {
        {
            return $this->render('home/pending.html.twig', [
                'controller_name' => 'HomeController',  ]);
        }
        }
        if($this->getUser()->getRoles()== ['client'])
            if($this->getUser()->getEtat()== ['baned'])
            {
                {
                    return $this->render('home/ban.html.twig', [
                        'controller_name' => 'HomeController',  ]);
                }
            }
        return $this->render('home/profile.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/pending', name: 'pending')]
    public function pending(): Response
    {

        return $this->render('home/pending.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/listAgent', name: 'listAgent')]
    public function listAgent(ManagerRegistry $doctrine): Response
    {
        $user= $doctrine->getRepository(Utilisateur::class)->findAll();
        return $this->render('home/listAgent.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/listClient', name: 'listClient')]
     public function listClient(ManagerRegistry $doctrine): Response
{
    $user= $doctrine->getRepository(Utilisateur::class)->findAll();
    return $this->render('home/listClient.html.twig', [
        'user' => $user,
    ]);
}

    #[Route('adminAprove/{idUser}', name: 'adminAprove')]
    public function adminApprove(ManagerRegistry $doctrine,$idUser,Request $req): Response
    {
        $em = $doctrine->getManager();
        $user = $doctrine->getRepository(Utilisateur::class)->find($idUser);
        $users = $doctrine->getRepository(Utilisateur::class)->find($idUser);
        $form = $this->createForm(AdminAproveType::class,$user);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em->persist($user);
            $em->flush();
            $roles=$user->getRoles();

            return $this->redirectToRoute('listAgent');

        }
        return $this->renderForm('home/adminAprove.html.twig',[
            'users' => $users,
            'form'=>$form
        ]);

}
    #[Route('adminBan/{idUser}', name: 'adminBan')]
    public function adminBan(ManagerRegistry $doctrine,$idUser,Request $req): Response
    {
        $em = $doctrine->getManager();
        $user = $doctrine->getRepository(Utilisateur::class)->find($idUser);
        $users = $doctrine->getRepository(Utilisateur::class)->find($idUser);
        $form = $this->createForm(AdminBanType::class,$user);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('listClient');
        }
        return $this->renderForm('home/adminBan.html.twig',[
            'users' => $users,
            'form'=>$form
        ]);
    }
}
