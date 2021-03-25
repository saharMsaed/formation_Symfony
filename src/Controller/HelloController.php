<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    /**
     * @Route("/hello/{world}", name="hello")
     */
    public function index($world): Response
    {
        return $this->render('hello/index.html.twig', [
            'controller_name' => $world,
        ]);
    }
}
