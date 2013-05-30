<?php

namespace Celsius\Celsius3Bundle\Handler;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationFailureHandler;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

class AuthenticationHandler extends DefaultAuthenticationFailureHandler
        implements AuthenticationFailureHandlerInterface,
        LogoutSuccessHandlerInterface
{
    private $router;

    public function __construct(Router $router, HttpKernelInterface $httpKernel,
            HttpUtils $httpUtils, array $options,
            LoggerInterface $logger = null)
    {
        parent::__construct($httpKernel, $httpUtils, $options, $logger);
        $this->router = $router;
    }

    public function onLogoutSuccess(Request $request)
    {
        $url = $request->getSession()->get('instance_url');

        return new RedirectResponse(
                $this->router->generate('public_index', array('url' => $url)));
    }

    public function onAuthenticationFailure(Request $request,
            AuthenticationException $exception)
    {
        $url = $request->attributes->get('url');

        $response = parent::onAuthenticationFailure($request, $exception);
        $response
                ->setTargetUrl(
                        $this->router
                                ->generate('fos_user_security_login',
                                        array('url' => $url)));

        return $response;
    }
}
