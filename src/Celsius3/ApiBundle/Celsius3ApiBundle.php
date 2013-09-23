<?php

namespace Celsius3\ApiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Celsius3\ApiBundle\DependencyInjection\Security\Factory\WsseFactory;

class Celsius3ApiBundle extends Bundle
{
    
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        
        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new WsseFactory());
    }
    
}
