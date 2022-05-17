<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    private const MAX_POSTS_PER_PAGE = 20;

    #[Route('/post')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy([], ['createdAt' => 'DESC'], self::MAX_POSTS_PER_PAGE);

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/post/{month}', requirements: ['month' => '\d{4}-((0[1-9])|(1[0-2]))'])]
    /**
     * @Route("/post/{month}", requirements={"month": "\d{4}-((0[1-9])|(1[0-2]))"})
     */
    public function indexByMonth(\DateTimeImmutable $month, PostRepository $postRepository): Response
    {
        $posts = $postRepository->findByMonthDQL($month);

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/post/{id}', requirements: ['id' => '\d+'])]
    /**
     * @Route("/post/{id}",requirements={"id": "\d+"})
     */
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/post/new', methods: ['GET', 'POST'])]
    /**
     * @Route("/post/new", methods={"GET", "POST"})
     */
    public function create(): Response
    {
        $post = new Post();
        $post->setCreatedAt(new \DateTimeImmutable());
        $form = $this->createFormBuilder($post)
            ->add('title')
            ->add('body')
            ->add('promoted')
            // ->add('validate', SubmitType::class) si le bouton doit faire partie du modèle
            ->getForm()
        ;

        return $this->renderForm('post/create.html.twig', [
            'create_form' => $form,
        ]);
    }
}
