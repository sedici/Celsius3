<?php

namespace Celsius\Celsius3Bundle\Handler;

use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthenticationHandler implements LogoutSuccessHandlerInterface
{

    public function onLogoutSuccess(Request $request)
    {
        //$url = $request->getSession()->get('instance_url');

        $request->getSession()->invalidate();

        return new RedirectResponse($request->getBaseUrl() . '/public');
    }

}