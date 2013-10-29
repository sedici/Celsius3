<?php

namespace Celsius3\CoreBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius3\CoreBundle\Document\Instance;

class AdministrationExtension extends \Twig_Extension
{

    private $container;
    private $environment;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
            'count_users' => new \Twig_Function_Method($this, 'countUsers'),
        );
    }

    public function countUsers(Instance $instance)
    {
        return $this->container
                        ->get('doctrine.odm.mongodb.document_manager')
                        ->getRepository('Celsius3CoreBundle:BaseUser')
                        ->countUsers($instance);
    }

    public function getName()
    {
        return 'celsius3_core.administration_extension';
    }

}
