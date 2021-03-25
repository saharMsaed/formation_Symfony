<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em= $em;
    }
    /**
     * @Route("/post/show/{slug}", name="post_show")
     */
    public function index($slug): Response
    {
        $post= $this->em->getRepository(Post::class)->findOneBySlug($slug);
        if (!$post){
            return $this->redirectToRoute('home');
        }

        return $this->render('post/index.html.twig', [
            'post' => $post,
        ]);
    }
}
