<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/hello")
 */
class HelloWorldController extends AbstractController
{
    /**
     * @Route("/world")
     */
    public function index(): Response
    {
        dump('test');

        return $this->render('hello_world/index.html.twig', [
            'controller_name' => 'HelloWorldController',
        ]);
    }

    /**
     * @Route(
     *     "/param/{int_id}",
     *     requirements={"int_id": "\d+"},
     *     methods={"POST", "PUT"}
     * )
     */
    public function httpMessages(int $int_id): Response
    {
        dump($int_id);

        return new Response('<p>le param est '.$int_id.'</p></body>');
    }
}
