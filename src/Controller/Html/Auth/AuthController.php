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

    // El logout está configurado en config/packages/security.yaml y config/routes.yaml porque Symfony maneja el logout automáticamente.

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
}
