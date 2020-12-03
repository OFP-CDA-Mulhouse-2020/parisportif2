<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserLoginType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class User2Controller extends AbstractController
{
    /**
     * @Route("/user2", name="user2")
     */
    public function index(): Response
    {
        return $this->render('user2/index.html.twig', [
            'controller_name' => 'User2Controller',
        ]);
    }


    /**
     * @Route("/user/form", name="user_form")
     */
    public function userForm(Request $request): Response
    {
        $form = $this->createForm(UserLoginType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userData = $form->getData();

            return $this->redirectToRoute('user_logged');
        }

        return $this->render('user2/index.html.twig', [
            'form' => $form->createView(),

        ]);
    }
    /*
    public function userFormCheck($userData, UserRepository $userRepository){
        $user = new User();
      //  $user->setEmail($userData['email']);
      //  $user->setPassword($userData['email']);

        $userFromDb = $userRepository->findBy(['email'=> $user->getEmail()]);

        if(! isset($userFromDb)){
            throw new InvalidArgumentException('email inexistant');
        }

    }
*/
    /**
     * @Route("/user/logged", name="user_logged")
     */
    public function userLogged(Request $request): Response
    {

        // $testUser = $userRepository->findBy(['email'=>'daniel.cda@test.com']);

        //  var_dump($testUser);
        return $this->render('home/welcome.html.twig', []);
    }
}
