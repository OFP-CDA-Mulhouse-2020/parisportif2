<?php

namespace App\Controller;

use App\Form\AddressType;
use App\Form\IdentityType;
use App\Form\LoginType;
use App\Form\EditPasswordType;
use App\Repository\AddressRepository;
use App\Security\LoginFormAuthenticator;
use App\Service\DatabaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * @Route("/app/profile", name="app_profile")
 */
class UserProfileController extends AbstractController
{
    /**
     * @Route("", name="")
     */
    public function userProfile(): Response
    {
        return $this->render('user_profile/user_profile.html.twig', [
            'controller_name' => 'UserProfileController',
        ]);
    }

    /**
     * @Route("/activation", name="_activation")
     */
    public function userProfileActivation(UserInterface $user): Response
    {

        return $this->render('user_profile/activation.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/suspend", name="_suspend")
     */
    public function userProfileSuspend(UserInterface $user): Response
    {

        return $this->render('user_profile/suspend.html.twig', [
            'user' => $user,
        ]);
    }

//    /**
//     * @Route("/suspend/process", name="_suspend")
//     */
//    public function userProfileSuspendProcess(Request $request, DatabaseService $databaseService): Response
//    {
        $user = $this->getUser();
//        dd($request->request->get('suspendType'));
//
//        if (!$request->request->get('suspendType')) {
//            return $this->render('user_profile/suspend.html.twig', [
//                'user' => $user,
//            ]);
//        }
//
//        $user->deactivate();
//        $user->suspend();
//
//        $databaseService->saveToDatabase($user);
//        $machin = $request->request->get('suspendType');
//        switch ($request->request->get('suspendType')) {
//            case "1":
//                $this->container->get('security.token_storage')->setToken(null);
//                $user->deactivate();
//                $user->suspend();
//
//                $databaseService->saveToDatabase($user);
//
//                $this->addFlash('success_delete_user', 'Votre compte utilisateur a bien été supprimé !');
//                return $this->redirectToRoute('app_login');
//                break;
//            case "2":
//                $this->container->get('security.token_storage')->setToken(null);
//                $user->deactivate();
//                $user->suspend();
//
//                $databaseService->saveToDatabase($user);
//
//                $this->addFlash(
//                    'success_delete_user',
//                    'Votre compte est suspendu jusqu\'au ' . $user->getSuspendedAt()
//                    . 'hello'
//                );
//                return $this->redirectToRoute('app_login');
//                break;
//            default:
//                return $this->render('user_profile/suspend.html.twig', [
//                    'user' => $user,
//                ]);
//        }
//    }


    /**
     * @Route("/delete", name="_delete")
     * @param DatabaseService $databaseService
     * @return Response
     */
    public function userProfileDelete(DatabaseService $databaseService): Response
    {
        // récupère mon utilisateur
        $user = $this->getUser();
        // suppression du token, on redirigera automatiquement(peu importe la redirection) vers la page de login
        $this->container->get('security.token_storage')->setToken(null);
        // désactivation de l'utilisateur
        $user->deactivate();
        // suppression de l'utilisateur
        $user->delete();
        // l'utilisateur perd son ROLE_USER
        $user->setRoles([]);
        // envoie des modifications vers la bdd
        $databaseService->saveToDatabase($user);

        $this->addFlash('success_delete_user', 'Votre compte utilisateur a bien été supprimé !');

        return $this->redirectToRoute('app_login');
    }
}
