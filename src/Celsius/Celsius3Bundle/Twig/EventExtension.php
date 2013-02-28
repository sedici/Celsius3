<?php

namespace Celsius\Celsius3Bundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius\Celsius3Bundle\Document\Event;
use Celsius\Celsius3Bundle\Document\Receive;
use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Document\MultiInstanceRequest;
use Celsius\Celsius3Bundle\Form\Type\OrderReceiveType;

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
        );
    }

    public function renderRequestEvent(Event $event, Order $order)
    {
        return $this->environment->render('CelsiusCelsius3Bundle:AdminOrder:_event_request.html.twig', array(
                    'event' => $event,
                    'isMultiInstance' => $event instanceof MultiInstanceRequest,
                    'order' => $order,
                    'receive_form' => $this->container->get('form.factory')->create(new OrderReceiveType(), new Receive())->createView(),
                ));
    }

    public function getName()
    {
        return 'event_extension';
    }

}