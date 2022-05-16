<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     *     methods={"GET", "PUT"}
     * )
     */
    public function httpMessages(int $int_id, Request $request): Response
    {
        dump($request->getPathInfo());
        // query est de type ParameterBag
        // données nativement accessible dans la super-globale $_GET
        dump($request->query->get('language', 'fr'));
        // données nativement accessible dans la super-globale $_POST
        dump($request->request->get('language', 'fr'));
        // données nativement accessible dans la super-globale $_SESSION
        dump($request->getSession()->get('language', 'fr'));
        dump($request->getLocale());
        dump($request->getPreferredLanguage(['en', 'fr', 'es']));
        dump($int_id);

        return new Response('<p>le param est '.$int_id.'</p></body>');
    }
}
