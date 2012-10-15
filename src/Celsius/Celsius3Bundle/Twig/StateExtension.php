<?php

namespace Celsius\Celsius3Bundle\Twig;

use Celsius\Celsius3Bundle\Manager\StateManager;
use Celsius\Celsius3Bundle\Document\Order;

class StateExtension extends \Twig_Extension
{

    private $manager;
    private $environment;

    public function __construct(StateManager $manager)
    {
        $this->manager = $manager;
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
        );
    }

    public function renderState($state, Order $order)
    {
        //$this->environment->render();
    }

    public function getPositiveStates()
    {
        return $this->manager->getPositiveStates();
    }

    public function getName()
    {
        return 'state_extension';
    }

}