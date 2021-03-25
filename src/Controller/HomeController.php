<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Category;
use App\Form\SearchType;
use App\Classes\SearchClass;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $em;

    public function __construct (EntityManagerInterface $em){
        $this->em= $em;
    }
    
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request): Response
    {
        $categories= $this->em->getRepository(Category::class)->findAll();
        $search= new SearchClass();
        $form= $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $posts= $this->em->getRepository(Post::class)->searchAndFilterByTags($search);
        } else $posts= $this->em->getRepository(Post::class)->findAll();

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'posts' => $posts,
            'form' => $form->createView()
        ]);
    }
}
