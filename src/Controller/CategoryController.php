<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/')]
    public function index(CategoryRepository $repository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $repository->findAll(),
        ]);
    }

    #[Route('/{id}')]
    public function postIndex(Category $category, PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy(['categorizedBy' => $category], ['createdAt' => 'DESC']);

        return $this->render('category/post_index.html.twig', [
            'category' => $category,
            'posts' => $posts,
        ]);
    }
}