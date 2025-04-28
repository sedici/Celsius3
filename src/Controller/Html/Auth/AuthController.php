<?php

namespace Celsius3\Controller\Html\Auth;

use Celsius3\Controller\Base\UserController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


#[
    Route('/auth')
]
class AuthController extends UserController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->htmlRenderer->setTemplatePrefix('Security/');
    }


    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Obtener el error de autenticación si existe
        $error = $authenticationUtils->getLastAuthenticationError();
        // Obtener el último nombre de usuario ingresado
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->htmlRenderer->render(
            'login',
            [
                'last_username' => $lastUsername,
                'error' => $error,
            ]
        );
    }


    #[Route('/logout', name: 'logout'), IsGranted('IS_AUTHENTICATED_FULLY')]
    public function logout(): Response
    {
        $this->session->invalidate();
        $this->tokenStorage->setToken(null);
        return $this->redirectToRoute('login');
    }
}
