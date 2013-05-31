<?php

namespace Celsius\Celsius3Bundle\Helper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Celsius\Celsius3Bundle\Exception\InstanceNotFoundException;

class InstanceHelper
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getSessionInstance()
    {
        $instance = $this->container
                ->get('doctrine.odm.mongodb.document_manager')
                ->getRepository('CelsiusCelsius3Bundle:Instance')
                ->find($this->container->get('session')->get('instance_id'));

        if (!$instance) {
            throw new InstanceNotFoundException('Unable to find Instance.');
        }

        return $instance;
    }

    public function getUrlInstance()
    {
        $instance = $this->container
                ->get('doctrine.odm.mongodb.document_manager')
                ->getRepository('CelsiusCelsius3Bundle:Instance')
                ->findOneBy(
                        array(
                                'url' => $this->container->get('request')
                                        ->attributes->get('url')));

        if (!$instance) {
            throw new InstanceNotFoundException('Unable to find Instance.');
        }

        return $instance;
    }

    public function getUrlOrSessionInstance()
    {
        $instance_url = $this->container->get('request')->attributes
                ->has('url') ? $this->container->get('request')->attributes
                        ->get('url')
                : $this->container->get('session')->get('instance_url');

        return $this->container->get('doctrine.odm.mongodb.document_manager')
                ->getRepository('CelsiusCelsius3Bundle:Instance')
                ->findOneBy(array('url' => $instance_url));
    }

    public function getSessionOrUrlInstance()
    {
        $instance_url = $this->container->get('session')->has('instance_url') ? $this
                        ->container->get('session')->get('instance_url')
                : $this->container->get('request')->attributes->get('url');

        return $this->container->get('doctrine.odm.mongodb.document_manager')
                ->getRepository('CelsiusCelsius3Bundle:Instance')
                ->findOneBy(array('url' => $instance_url));
    }
}
