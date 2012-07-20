<?php

namespace Celsius\Celsius3Bundle\Aop;

use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius\Celsius3Bundle\Document\Login;

class LoginLoggingInterceptor implements MethodInterceptorInterface
{

    private $container;
    private $dm;

    public function __construct(ContainerInterface $container, DocumentManager $dm)
    {
        $this->container = $container;
        $this->dm = $dm;
    }

    public function intercept(MethodInvocation $invocation)
    {
        $ip = $this->container->get('request')->getClientIp();

        $log = new Login();
        $log->setCategory('login');
        $log->setDate(time());

        $token = null;

        try
        {
            $token = $invocation->proceed();
            $user = $token->getUsername();
            $log->setMessage(sprintf('%s - User "%s" is now logged in from the IP "%s".', date('Y-m-d H:i:s'), $user, $ip));
        } catch (AuthenticationException $e)
        {
            $user = $this->container->get('request')->request->get('_username');
            $password = $this->container->get('request')->request->get('_password');
            $log->setMessage(sprintf('%s - User "%s" tried to login with the password "%s" from the IP "%s".', date('Y-m-d H:i:s'), $user, $password, $ip));
            $this->dm->persist($log);
            $this->dm->flush();
            throw $e;
        }

        $this->dm->persist($log);
        $this->dm->flush();

        return $token;
    }

}
