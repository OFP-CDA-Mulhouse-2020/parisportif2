<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    /**
     * @Route("app/email")
     */
    public function sendEmail(MailerService $mailerService): Response
    {
        $user = $this->getUser();
        assert($user instanceof User);

        $email = $mailerService->generateEmail($user);

        $mailerService->sendEmail($email);

        return $this->redirectToRoute('app');
    }
}
