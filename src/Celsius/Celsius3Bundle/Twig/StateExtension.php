<?php

namespace Celsius\Celsius3Bundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius\Celsius3Bundle\Document\Order;

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
            'render_state_header' => new \Twig_Function_Method($this, 'renderStateHeader'),
            'get_positive_states' => new \Twig_Function_Method($this, 'getPositiveStates'),
            'render_state_body' => new \Twig_Function_Method($this, 'renderStateBody'),
        );
    }

    public function renderStateHeader($state, Order $order)
    {
        return $this->environment->render('CelsiusCelsius3Bundle:AdminOrder:_state.html.twig', $this->container->get('state_manager')->getDataForHeaderRendering($state, $order));
    }

    public function renderStateBody($state, Order $order)
    {
        return $this->environment->render('CelsiusCelsius3Bundle:AdminOrder:_info_' . $state . '.html.twig', $this->container->get('state_manager')->getDataForBodyRendering($state, $order));
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