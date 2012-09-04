<?php

namespace Celsius\Celsius3Bundle\Aop;

use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class InstanceInjectionInterceptor implements MethodInterceptorInterface
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
        if ($this->container->get('request')->attributes->has('url'))
            $instance_url = $this->container->get('request')->attributes->get('url');
        else
            $instance_url = $this->container->get('session')->get('instance_url');

        $instance = $this->dm
                ->getRepository('CelsiusCelsius3Bundle:Instance')
                ->findOneBy(array('url' => $instance_url));
        
        $invocation->arguments[1]['instance'] = $instance;
        
        return $invocation->proceed();
    }

}