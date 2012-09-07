<?php

namespace Celsius\Celsius3Bundle\Aop;

use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius\Celsius3Bundle\Helper\ConfigurationHelper;

class InstanceLanguageInterceptor implements MethodInterceptorInterface
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
        $path = explode('/', $invocation->arguments[0]);

        if (!array_key_exists($path[1], ConfigurationHelper::$languages) && !$this->container->get('request')->isXmlHttpRequest())
        {
            if ($this->container->get('session')->has('instance_url'))
                $instance_url = $this->container->get('session')->get('instance_url');
            else
                $instance_url = $path[1];

            $instance = $this->dm
                    ->getRepository('CelsiusCelsius3Bundle:Instance')
                    ->findOneBy(array('url' => $instance_url));

            if ($instance)
                $invocation->arguments[0] = '/' . $instance->get('instance_language')->getValue() . $invocation->arguments[0];
        }

        return $invocation->proceed();
    }

}