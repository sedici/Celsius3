<?php

namespace Celsius\Celsius3Bundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Document\SingleInstanceRequest;
use Celsius\Celsius3Bundle\Form\Type\OrderRequestType;

class StateExtension extends \Twig_Extension
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
            'render_state' => new \Twig_Function_Method($this, 'renderState'),
            'get_positive_states' => new \Twig_Function_Method($this, 'getPositiveStates'),
            'render_state_info' => new \Twig_Function_Method($this, 'renderStateInfo'),
        );
    }

    public function renderState($state, Order $order)
    {
        $instance = $this->container->get('instance_helper')->getSessionInstance();

        return $this->environment->render('CelsiusCelsius3Bundle:AdminOrder:_state.html.twig', array(
                    'state' => $state,
                    'order' => $order,
                    'hasPrevious' => $order->hasState($this->container->get('state_manager')->getPreviousPositiveState($state), $instance),
                    'instance' => $instance,
                ));
    }

    public function renderStateInfo($state, Order $order)
    {
        $instance = $this->container->get('instance_helper')->getSessionInstance();

        return $this->environment->render('CelsiusCelsius3Bundle:AdminOrder:_info_' . $state . '.html.twig', array(
                    'state' => $state,
                    'order' => $order,
                    'events' => $this->container->get('state_manager')->getEventsToState($state),
                    'hasPrevious' => $order->hasState($this->container->get('state_manager')->getPreviousPositiveState($state), $instance),
                    'isCurrent' => $order->getCurrentState($instance)->getType()->getName() == $state,
                    'request_form' => $this->container->get('form.factory')->create(new OrderRequestType($this->container->get('doctrine.odm.mongodb.document_manager'), 'Celsius\\Celsius3Bundle\\Document\\SingleInstanceRequest'), new SingleInstanceRequest())->createView(),
                    'isDelivered' => $order->getState('delivered', $instance),
                    'instance' => $instance,
                ));
    }

    public function getPositiveStates()
    {
        return $this->container->get('state_manager')->getPositiveStates();
    }

    public function getName()
    {
        return 'state_extension';
    }

}