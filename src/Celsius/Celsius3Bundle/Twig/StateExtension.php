<?php

namespace Celsius\Celsius3Bundle\Twig;

use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius\Celsius3Bundle\Manager\StateManager;
use Celsius\Celsius3Bundle\Document\Order;

class StateExtension extends \Twig_Extension
{

    private $manager;
    private $environment;
    private $dm;

    public function __construct(StateManager $manager, DocumentManager $dm)
    {
        $this->manager = $manager;
        $this->dm = $dm;
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

    public function renderState($state, Order $order, $extra)
    {
        $createdEvents = array();
        if ($order->hasState($state))
        {
            $createdEvents = $this->dm->getRepository('CelsiusCelsius3Bundle:Event')
                    ->createQueryBuilder()
                    ->hydrate(false)
                    ->select('date')
                    ->field('state.id')->equals($order->getState($state)->getId())
                    ->getQuery()
                    ->execute();
        }
        return $this->environment->render('CelsiusCelsius3Bundle:AdminOrder:_state.html.twig', array(
                    'state' => $state,
                    'order' => $order,
                    'events' => $this->manager->getEventsToState($state),
                    'hasPrevious' => $order->hasState($this->manager->getPreviousPositiveState($state)),
                    'script' => 'CelsiusCelsius3Bundle:AdminOrder:_script_' . $state . '.js.twig',
                    'extra' => $extra,
                    'createdEvents' => $createdEvents,
                ));
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