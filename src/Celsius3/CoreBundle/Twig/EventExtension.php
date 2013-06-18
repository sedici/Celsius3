<?php

namespace Celsius3\CoreBundle\Twig;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius3\CoreBundle\Document\Event;
use Celsius3\CoreBundle\Document\Order;
use Celsius3\CoreBundle\Document\MultiInstanceRequest;

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
                'render_request_event' => new \Twig_Function_Method($this,
                        'renderRequestEvent'),
                'render_receive_event' => new \Twig_Function_Method($this,
                        'renderReceiveEvent'),
                'get_request_state' => new \Twig_Function_Method($this,
                        'getRequestState'),);
    }

    public function renderRequestEvent(Event $event, Order $order)
    {
        return $this->environment
                ->render(
                        'Celsius3CoreBundle:AdminOrder:_event_request.html.twig',
                        $this->container->get('celsius3_core.event_manager')
                                ->getDataForRequestRendering($event, $order));
    }

    public function renderReceiveEvent(Event $event, Order $order)
    {
        return $this->environment
                ->render(
                        'Celsius3CoreBundle:AdminOrder:_event_receive.html.twig',
                        $this->container->get('celsius3_core.event_manager')
                                ->getDataForReceiveRendering($event, $order));
    }

    public function getRequestState(MultiInstanceRequest $request)
    {
        return $request->getOrder()
                ->getCurrentState($request->getRemoteInstance());
    }

    public function getName()
    {
        return 'celsius3_core.event_extension';
    }

}
