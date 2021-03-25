<?php

namespace App\Controller;

use App\Form\UpdatePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordController extends AbstractController
{
    /**
     * @Route("/update_password", name="update_password")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em): Response
    {
        $user= $this->getUser();
        $form= $this->createForm(UpdatePasswordType::class, $user);
         
        $form->handleRequest($request);
        $notification= null;

        if ($form->isSubmitted() && $form->isValid()) {
            $old_pwd= $form->get('old_password')->getData();
           
            if ($encoder->isPasswordValid($user, $old_pwd)){
                $new_pwd= $form->get('new_password')->getData();
                $password= $encoder->encodePassword($user, $new_pwd);
                $user->setPassword($password);
                $em->flush();
                $notification= 'Votre mot de passe a été bien modifié';
            }else{
                $notification= 'Votre mot de passe est invalid';
            }
        }

        return $this->render('password/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
