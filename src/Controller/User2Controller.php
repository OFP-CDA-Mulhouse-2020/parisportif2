<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserLoginType2;
use App\Form\UserSubscribeType;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
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
     * @Route("/connexion", name="home_connexion")
     */
    public function userConnexion(Request $request, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserLoginType2::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $userFromDb = $userRepository->findOneBy(['email' => $user->getEmail()]);

            var_dump($userFromDb);

            if ($user->getPassword() === $userFromDb->getPassword()) {
                $this->get('session')->set('user', $userFromDb);
                return $this->redirectToRoute('home_logged');
            }

            return $this->render('user2/subscribeForm.html.twig', [
                'form' => $form->createView(),

            ]);
        }

        return $this->render('user2/connexionForm.html.twig', [
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
     * @Route("/subscribe", name="home_subscribe")
     */
    public function userSubscribe(Request $request, EntityManagerInterface $em, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserSubscribeType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setCreateDate(new DateTime())
            ->setUserValidation(false)
            ->setUserSuspended(true)
            ->setUserDeleted(false);

            $userAlreadyOnDb = $userRepository->findBy(['email' => $user->getEmail()]);

            if (! empty($userAlreadyOnDb)) {
                $em->persist($user);
                $em->flush();

                $userFromDb = $userRepository->findOneBy(['email' => $user->getEmail()]);

                $this->get('session')->set('user', $userFromDb);

                return $this->redirectToRoute('home_logged');
            }

            var_dump($userAlreadyOnDb);

            return $this->render('user2/subscribeForm.html.twig', [
                'form' => $form->createView(),

            ]);
        }

        return $this->render('user2/subscribeForm.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/home/logged", name="home_logged")
     */
    public function userLogged(Request $request): Response
    {
        $session = $request->getSession();
        $user = $session->get('user');
      //  dd($session->get('user'));

        return $this->render('home/welcome.html.twig', []);
    }
}
