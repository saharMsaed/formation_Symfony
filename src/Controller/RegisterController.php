<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em= $em;
    }


    /**
     * @Route("/register", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user= new User();
        $form= $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);
        $notification= null;
        if ($form->isSubmitted() && $form->isValid()) {
            $user= $form->getData();
            $searchedUser= $this->em->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
            if (!$searchedUser){
                $plainPassword = $user->getPassword();
                $encoded = $encoder->encodePassword($user, $plainPassword);
                $user->setPassword($encoded);
                $this->em->persist($user);
                $this->em->flush();
                $notification= "Votre compte a été bien enregistré";
            } else{
                $notification= "Erreur, votre compte n'a pas été enregistré";
            }
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
