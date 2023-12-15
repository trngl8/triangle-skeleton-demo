<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function index(AuthenticationUtils $authenticationUtils, string $adminEmail): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $error = $authenticationUtils->getLastAuthenticationError();

            $lastUsername = $authenticationUtils->getLastUsername();

            return $this->render('default/login.html.twig', [ // the same is login/index.html.twig
                'error' => $error,
                'last_username' => $lastUsername,
            ]);
        }

        if ($adminEmail === $user->getUserIdentifier()) {
            return $this->redirectToRoute('admin');
        }

        return $this->redirectToRoute('app_profile');
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): void
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
