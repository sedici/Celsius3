<?php
namespace Celsius3\CoreBundle\Handler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Celsius3\CoreBundle\Manager\UserManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CustomAuthenticationSuccessHandler extends
        DefaultAuthenticationSuccessHandler implements
        AuthenticationSuccessHandlerInterface
{
    private $router;
    private $security_context;

    public function __construct(Router $router,
            SecurityContextInterface $security_context, HttpUtils $httpUtils,
            array $options)
    {
        parent::__construct($httpUtils, $options);
        $this->router = $router;
        $this->security_context = $security_context;
    }

    public function onAuthenticationSuccess(Request $request,
            TokenInterface $token)
    {
        $response = parent::onAuthenticationSuccess($request, $token);
        if ($this->security_context
                ->isGranted(array(UserManager::ROLE_SUPER_ADMIN))) {
            $response
                    ->setTargetUrl(
                            $this->router->generate('superadministration'));
        } elseif ($this->security_context
                ->isGranted(array(UserManager::ROLE_ADMIN))) {
            $response->setTargetUrl($this->router->generate('administration'));
        } else {
            $response->setTargetUrl($this->router->generate('user_index'));
        }

        return $response;
    }
}
