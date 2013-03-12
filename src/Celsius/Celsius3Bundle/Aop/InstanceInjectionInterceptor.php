<?php

namespace Celsius\Celsius3Bundle\Aop;

use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Celsius\Celsius3Bundle\Helper\InstanceHelper;

class InstanceInjectionInterceptor implements MethodInterceptorInterface
{

    private $instance_helper;

    public function __construct(InstanceHelper $instance_helper)
    {
        $this->instance_helper = $instance_helper;
    }

    public function intercept(MethodInvocation $invocation)
    {
        $invocation->arguments[1]['instance'] = $this->instance_helper->getUrlOrSessionInstance();
        
        return $invocation->proceed();
    }

}