<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostCrudController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em= $em;
    }

    /**
     * @Route("/post/list", name="post_list")
     */
    public function index(): Response
    {
        $posts= $this->em->getRepository(Post::class)->findByCreator($this->getUser());

        return $this->render('post_crud/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/post/create", name="post_create")
     */
    public function new(Request $request): Response
    {
        $post= new Post();
        $form= $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $post->setCreator($this->getUser());
            $this->em->persist($post);
            $this->em->flush(); 
            return $this->redirectToRoute('post_list');
        }
        return $this->render('post_crud/new.html.twig', [
           'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/post/show/creator/{slug}", name="post_creator_show")
     */
    public function show($slug): Response
    {
        $post= $this->em->getRepository(Post::class)->findOneBySlug($slug);
        if ($post == null) return $this->redirectToRoute('post_list');
        if ($post->getCreator() != $this->getUser()){
            return $this->redirectToRoute('post_list');
        }

        return $this->render('post_crud/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/post/edit/creator/{id}", name="post_creator_edit")
     */
    public function edit(Post $post, Request $request): Response
    {
        if ($post == null) $this->redirectToRoute('post_list');
        if ($post->getCreator() != $this->getUser()) return $this->redirectToRoute('post_list');
        
        $form= $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->em->flush(); 
            return $this->redirectToRoute('post_list');
        }

        return $this->render('post_crud/edit.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/post/delete/creator/{id}", name="post_creator_delete")
     */
    public function delete(Post $post): Response
    {
        if ($post == null) $this->redirectToRoute('post_list');
        if ($post->getCreator() != $this->getUser()) return $this->redirectToRoute('post_list');
        $this->em->remove($post);
        $this->em->flush(); 
        return $this->redirectToRoute('post_list');
    }
}
