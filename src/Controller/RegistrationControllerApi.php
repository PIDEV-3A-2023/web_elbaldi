<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;

class RegistrationControllerApi extends AbstractController
{
    #[Route(path: '/loginApiTest', name: 'loginApiTest')]
    public function login(AuthenticationUtils $authenticationUtils, UserPasswordHasherInterface $userPasswordHasher,Request $request): Response
    {
        $email = $request->query->get("email");
        $password = $request->query->get("password");

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Utilisateur::class)->findOneBy(['email' => $email]);
        $validPassword = $userPasswordHasher->isPasswordValid($user, $password);
        if ($user) {
            if ($validPassword) {
                $normalizer = new ObjectNormalizer();
                $serializer = new \Symfony\Component\Serializer\Serializer(array(new DateTimeNormalizer(), $normalizer));
                $formatted = $serializer->normalize($user,null,array('attributes' => array(
                    'idUser', 'nom', 'prenom', 'email','numtel','ville','mdp')));
                return new JsonResponse($formatted);
            } else {
                return new JsonResponse(['error' => "Wrong Password"], 403);
            }
        } else {
            return new JsonResponse(['error' => "Please verify your username or password"], 403);
        }
    }
    #[Route(path: '/registerApi', name: 'registerApi')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager)
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new JsonResponse('Already connected');
        }
        $user = new Utilisateur();
        $user->setNom($request->get('Nom'));
        $user->setPrenom($request->get('Prenom'));
        $user->setEmail($request->get('email'));
        $password = $request->get('Password');
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $password
            )
        );
        $email = $request->get('email');
        dump($user);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse("Please enter a valid email", 500);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return new JsonResponse("Account Created", 200);
    }
}