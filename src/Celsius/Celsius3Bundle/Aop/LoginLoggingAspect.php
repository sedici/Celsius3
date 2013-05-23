<?php

namespace Celsius\Celsius3Bundle\Aop;
use JMS\AopBundle\Aop\PointcutInterface;
use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Celsius\Celsius3Bundle\Document\Login;

class LoginLoggingAspect implements MethodInterceptorInterface, PointcutInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function matchesClass(\ReflectionClass $class)
    {
        return false
                !== strpos($class->name,
                        'UsernamePasswordFormAuthenticationListener');
    }

    public function matchesMethod(\ReflectionMethod $method)
    {
        return false !== strpos($method->name, 'attemptAuthentication');
    }

    public function intercept(MethodInvocation $invocation)
    {
        $ip = $this->container->get('request')->getClientIp();

        $log = new Login();
        $log->setCategory('login');
        $log->setDate(time());

        try {
            $token = $invocation->proceed();
            $user = $token->getUsername();
            $log
                    ->setMessage(
                            sprintf(
                                    '%s - User "%s" is now logged in from the IP "%s".',
                                    date('Y-m-d H:i:s'), $user, $ip));
            $this->container->get('doctrine.odm.mongodb.document_manager')
                    ->persist($log);
            $this->container->get('doctrine.odm.mongodb.document_manager')
                    ->flush();
            return $token;
        } catch (AuthenticationException $e) {
            $user = $this->container->get('request')->request->get('_username');
            $password = $this->container->get('request')->request
                    ->get('_password');
            $log
                    ->setMessage(
                            sprintf(
                                    '%s - User "%s" tried to login with the password "%s" from the IP "%s".',
                                    date('Y-m-d H:i:s'), $user, $password, $ip));
            $this->container->get('doctrine.odm.mongodb.document_manager')
                    ->persist($log);
            $this->container->get('doctrine.odm.mongodb.document_manager')
                    ->flush();
            throw $e;
        }
    }
}
