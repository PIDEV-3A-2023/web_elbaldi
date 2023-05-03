<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Utilisateur;
use App\Form\ForgetPasswordType;
use App\Form\ModifierProfileType;
use App\Form\NewPasswordType;
use App\Form\ResetPasswordType;
use App\Form\UpdateProfileType;
use App\Form\VerificationCodeFormType;
use App\Form\VerificationCodeType;
use App\Repository\UserRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twilio\Rest\Client;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('delete/{idUser}', name: 'delete')]
    public function deleteAccount(ManagerRegistry $doctrine, $idUser): Response
    {

        $em = $doctrine->getManager();
        $user = $doctrine->getRepository(Utilisateur::class)->find($idUser);
        $em->remove($user);
        $em->flush();


        return $this->redirectToRoute('listClient');
    }
    #[Route('updateProfile/{idUser}', name: 'updateProfile')]
    public function updateProfile(ManagerRegistry $doctrine, $idUser, Request $req): Response
    {


        $em = $doctrine->getManager();
        $user = $doctrine->getRepository(Utilisateur::class)->find($idUser);
        $form = $this->createForm(UpdateProfileType::class, $user);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $em->persist($user);
            $em->flush();


            return $this->redirectToRoute('profile');
        }

        return $this->renderForm('home/modifierProfile.html.twig', ['form' => $form]);
    }
    #[Route(path: '/forgetPassword', name: 'forgetPassword')]
    public function forgetPassword(Request $request, UtilisateurRepository $user, TokenGeneratorInterface $tokenGenerator): Response
    {
        $form = $this->createForm(ForgetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $donnees = $form->getData();


            $recevier = $user->findOneByNumero($donnees->getNumtel());
            if ($recevier == null) {
                return $this->redirectToRoute('forgetPassword');
            } else
                $idsms = $recevier->getIdUser();
            $tokensalt = bin2hex(random_bytes(2));

            try {
                $recevier->setResetToken($tokensalt);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($recevier);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());

                return $this->redirectToRoute('forgetPassword');
            }
            $sid = 'ACb6357a110c9e1974aa0e9d7a0acc0bcc';
            $token = 'f095ecd292bc6cd1c7937dc6c8968de5';
            $client = new Client($sid, $token);

            $client->messages->create(
                // the number you'd like to send the message to
                $recevier = '+21654900361',
                [
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => '+13157918686',
                    // the body of the text message you'd like to send
                    'body' => 'Your verification code is :' . $tokensalt
                ]
            );

            return $this->redirectToRoute('code_app', ['idsms' => $idsms]);
        }
        return $this->render(
            'home/forgetPassword.html.twig',
            ['form' => $form->createView()]
        );
    }
    #[Route(path: '/verificationCode/{idsms}', name: 'code_app')]
    public function VerificationCode(Request $request, UtilisateurRepository $user, ManagerRegistry $doctrine, $idsms): Response
    {

        $form = $this->createForm(VerificationCodeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $donnees = $form->getData();
            $recevier = $user->findOneBySalt($donnees->getResetToken());

            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($recevier);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());

                return $this->redirectToRoute('forgetPassword');
            }

            return $this->redirectToRoute('newPassword', ['idsms' => $idsms]);
        }
        return $this->render(
            'home/verificationCode.html.twig',
            ['form' => $form->createView()]
        );
    }
    #[Route('newPassword/{idsms}', name: 'newPassword')]
    public function newPassword(ManagerRegistry $doctrine, $idsms, Request $req, UserPasswordHasherInterface $userPasswordHasher): Response
    {

        $em = $doctrine->getManager();
        $user = $doctrine->getRepository(Utilisateur::class)->find($idsms);
        $user->setPassword("");

        $form = $this->createForm(NewPasswordType::class, $user);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_login');
        }

        return $this->renderForm('home/newPassword.html.twig', ['form' => $form]);
    }
}
