<?php

namespace Celsius\Celsius3Bundle\Helper;

use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Document\State;
use Celsius\Celsius3Bundle\Document\Event;
use Celsius\Celsius3Bundle\Manager\StateManager;

class LifecycleHelper
{

    private $dm;
    private $manager;

    public function __construct($dm, StateManager $manager)
    {
        $this->dm = $dm;
        $this->manager = $manager;
    }

    protected function setEventData(Event $event, Order $order, $state, $date)
    {
        $event->setDate($date);
        $event->setOperator($order->getOperator());
        $event->setInstance($order->getInstance());
        $event->setOrder($order);
        $event->setState($this->createState($state, $date, $order));

        $this->dm->persist($event);
        $this->dm->flush();
    }

    protected function createState($name, $date, $order)
    {
        $state = new State();
        $state->setDate($date);
        $state->setInstance($order->getInstance());
        $state->setOrder($order);
        $state->setType(
                $this->dm->getRepository('CelsiusCelsius3Bundle:StateType')
                        ->createQueryBuilder()
                        ->field('name')->equals($name)
                        ->getQuery()
                        ->getSingleResult()
        );
        $state->setPrevious($order->getCurrentState());

        $this->dm->persist($state);

        $order->setCurrentState($state);

        $this->dm->persist($order);

        return $state;
    }

    /**
     * Receives the event name and the order document and creates the appropiate
     * event and state
     * 
     * @param string $name The event name
     * @param Celsius\Celsius3Bundle\Document\Order $order The Order document 
     */
    public function createEvent($name, Order $order)
    {
        $stateName = $this->manager->getStateForEvent($name);
        
        if (!$order->hasState($this->manager->getPreviousPositiveState($stateName)) && $name != 'creation')
        {
            throw $this->manager->createNotFoundException('State not found');
        }
        
        $date = date('Y-m-d H:i:s');
        
        $orderDateMethod = 'set' . ucfirst($stateName);
        $eventClassName = $this->manager->getFullClassNameForEvent($name);
        
        $order->$orderDateMethod($date);
        $this->setEventData(new $eventClassName, $order, $stateName, $date);
    }

}