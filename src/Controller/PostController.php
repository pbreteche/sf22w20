<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    #[IsGranted('IS_AUTHENTICATED')]
    /**
     * @Route("/post/new", methods={"GET", "POST"})
     * @IsGranted("IS_AUTHENTICATED")
     */
    public function create(Request $request, PostRepository $postRepository): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED')) {
            throw $this->createAccessDeniedException('Vous devez être authentifié');
        }

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $post = new Post();
        $post->setAuthor($this->getUser());
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postRepository->add($post, true);
            $this->addFlash('success', 'La publication a bien été enregistrée.');

            return $this->redirectToRoute('app_post_show', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/create.html.twig', [
            'create_form' => $form,
        ]);
    }

    #[Route('/post/{id}/edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[Security('is_granted("IS_AUTHOR", post) or is_granted("ROLE_ADMIN")')]
    public function edit(Post $post, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $doctrine->getManager();
            $manager->flush();
            $this->addFlash('success', 'La publication a bien été modifiée.');

            return $this->redirectToRoute('app_post_show', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/edit.html.twig', [
            'edit_form' => $form,
        ]);
    }
}
