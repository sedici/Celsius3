<?php

namespace Celsius\Celsius3Bundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius\Celsius3Bundle\Document\Event;
use Celsius\Celsius3Bundle\Document\Order;

class EventExtension extends \Twig_Extension
{

    private $environment;
    private $container;

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
            'render_request_event' => new \Twig_Function_Method($this, 'renderRequestEvent'),
            'render_receive_event' => new \Twig_Function_Method($this, 'renderReceiveEvent'),
        );
    }

    public function renderRequestEvent(Event $event, Order $order)
    {
        return $this->environment->render('CelsiusCelsius3Bundle:AdminOrder:_event_request.html.twig', $this->container->get('event_manager')->getDataForRequestRendering($event, $order));
    }

    public function renderReceiveEvent(Event $event, Order $order)
    {
        return $this->environment->render('CelsiusCelsius3Bundle:AdminOrder:_event_receive.html.twig', $this->container->get('event_manager')->getDataForReceiveRendering($event, $order));
    }

    public function getName()
    {
        return 'event_extension';
    }

}