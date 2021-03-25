<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{id}", name="category")
     */
    public function index(Category $category): Response
    {
        if (!$category){
            return $this->redirectToRoute('home');
        }

        return $this->render('category/index.html.twig', [
            'category' => $category,
        ]);
    }
}
