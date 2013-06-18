<?php

namespace Celsius3\CoreBundle\Handler;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class CustomLogoutSuccessHandler implements LogoutSuccessHandlerInterface
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function onLogoutSuccess(Request $request)
    {
        $url = $request->getSession()->get('instance_url');

        return new RedirectResponse(
                $this->router->generate('public_index', array('url' => $url)));
    }
}
