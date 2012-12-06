<?php

namespace Celsius\Celsius3Bundle\Handler;

use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthenticationHandler implements AuthenticationFailureHandlerInterface, LogoutSuccessHandlerInterface
{

    private $router;

    public function __construct($router)
    {
        $this->router = $router;
    }

    public function onLogoutSuccess(Request $request)
    {
        $url = $request->getSession()->get('instance_url');

        return new RedirectResponse($this->router->generate('public_index', array('url' => $url)));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $url = $request->attributes->get('url');
        
        return new RedirectResponse($this->router->generate('fos_user_security_login', array('url' => $url)));
    }

}