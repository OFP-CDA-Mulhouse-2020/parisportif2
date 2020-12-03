<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User1;
use App\Form\UserLoginType;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(): Response
    {
        //     return $this->render('login/index.html.twig', [
        //         'controller_name' => 'LoginController',
        //     ]);
        // }

        $user = new User1();

        $form = $this->createForm(UserLoginType::class, $user);

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
