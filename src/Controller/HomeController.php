<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {
        var_dump('hello');
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


        /**
     * @Route("/home/test", name="home_test")
     */
    public function index2(): Response
    {
        var_dump('test2');
        phpinfo();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
