<?php

namespace Celsius3\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Obtener el error de autenticación si existe
        $error = $authenticationUtils->getLastAuthenticationError();
        // Obtener el último nombre de usuario ingresado
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Este método puede quedar vacío, Symfony lo intercepta
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
