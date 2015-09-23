<?php

namespace Celsius3\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FOSUserOverridePass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        $container->getDefinition('fos_user.listener.authentication')->setClass('Celsius3\CoreBundle\EventListener\AuthenticationListener');
    }

}
