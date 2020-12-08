<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\LoginType;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends AbstractController
{
    /**
     * @Route("/login2", name="login2")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('loginCheck');
        }

        return $this->render('login/index.html.twig', [
            'login_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/login/check", name="loginCheck")
     */
    public function loginFormCheck(): Response
    {
        return $this->render('login/check.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
}
