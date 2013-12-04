<?php

namespace Celsius3\CoreBundle\Aop;

use JMS\AopBundle\Aop\PointcutInterface;
use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Celsius3\CoreBundle\Document\Login;

class LoginLoggingAspect implements MethodInterceptorInterface, PointcutInterface
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function matchesClass(\ReflectionClass $class)
    {
        return false !== strpos($class->name, 'UsernamePasswordFormAuthenticationListener');
    }

    public function matchesMethod(\ReflectionMethod $method)
    {
        return false !== strpos($method->name, 'attemptAuthentication');
    }

    public function intercept(MethodInvocation $invocation)
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $ip = $request->getClientIp();

        $log = new Login();
        $log->setCategory('login');
        $log->setDate(time());

        $dm = $this->container->get('doctrine.odm.mongodb.document_manager');

        try {
            $token = $invocation->proceed();
            $user = $token->getUsername();
            $log->setMessage(sprintf('%s - User "%s" is now logged in from the IP "%s".', date('Y-m-d H:i:s'), $user, $ip));

            $dm->persist($log);
            $dm->flush();
            return $token;
        } catch (AuthenticationException $e) {
            $user = $request->request->get('_username');
            $password = $request->request->get('_password');
            $log->setMessage(sprintf('%s - User "%s" tried to login with the password "%s" from the IP "%s".', date('Y-m-d H:i:s'), $user, $password, $ip));
            $dm->persist($log);
            $dm->flush();
            throw $e;
        }
    }

}
