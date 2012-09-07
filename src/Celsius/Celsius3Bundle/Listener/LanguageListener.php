<?php

namespace Celsius\Celsius3Bundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use FOS\UserBundle\Model\UserInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class LanguageListener
{

    private $container;
    private $dm;

    public function __construct(ContainerInterface $container, DocumentManager $dm)
    {
        $this->container = $container;
        $this->dm = $dm;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        print_r($event->getRequest()->attributes->get('url'));
//        die();
//        die();
//        if (!$this->container->get('request')->attributes->has('_locale'))
//        {
//            if ($this->container->get('request')->attributes->has('url'))
//                $instance_url = $this->container->get('request')->attributes->get('url');
//            else
//                $instance_url = $this->container->get('session')->get('instance_url');
//
//            $instance = $this->dm
//                    ->getRepository('CelsiusCelsius3Bundle:Instance')
//                    ->findOneBy(array('url' => $instance_url));
//
//            $locale = $instance->get('instance_language')->getValue();
//
//            $this->container->get('request')->attributes->set('_locale', $locale);
//        }
    }

}
