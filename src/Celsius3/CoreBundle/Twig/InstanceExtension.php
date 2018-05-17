<?php

namespace Celsius3\CoreBundle\Twig;

use Doctrine\ORM\EntityManager;

class InstanceExtension extends \Twig_Extension
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getFunctions()
    {
        return array(new \Twig_SimpleFunction('get_instance_url', array($this, 'getInstanceUrl')));
    }

    public function getInstanceUrl($id)
    {
        $instance = $this->entityManager->getRepository('Celsius3CoreBundle:Instance')->find($id);

        return $instance->getUrl();
    }
}