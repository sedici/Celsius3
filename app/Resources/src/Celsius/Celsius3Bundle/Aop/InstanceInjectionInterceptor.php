<?php

namespace Celsius\Celsius3Bundle\Aop;

use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Doctrine\ODM\MongoDB\DocumentManager;

class InstanceInjectionInterceptor implements MethodInterceptorInterface
{

    private $request;
    private $session;
    private $dm;

    public function __construct($request, $session, DocumentManager $dm)
    {
        $this->request = $request;
        $this->session = $session;
        $this->dm = $dm;
    }

    public function intercept(MethodInvocation $invocation)
    {
        if ($this->request->attributes->has('url'))
            $instance_url = $this->request->attributes->get('url');
        else
            $instance_url = $this->session->get('instance_url');

        $instance = $this->dm
                ->getRepository('CelsiusCelsius3Bundle:Instance')
                ->findOneBy(array('url' => $instance_url));
        
        $invocation->arguments[1]['instance'] = $instance;
        
        return $invocation->proceed();
    }

}