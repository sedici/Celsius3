<?php

namespace Celsius\Celsius3Bundle\Helper;

use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Document\State;
use Celsius\Celsius3Bundle\Document\Event;
use Celsius\Celsius3Bundle\Document\MultiInstanceRequest;
use Celsius\Celsius3Bundle\Document\SingleInstanceRequest;
use Celsius\Celsius3Bundle\Document\Instance;
use Celsius\Celsius3Bundle\Manager\StateManager;

class LifecycleHelper
{

    private $dm;
    private $manager;

    public function __construct(DocumentManager $dm, StateManager $manager)
    {
        $this->dm = $dm;
        $this->manager = $manager;
    }

    /**
     * @todo Mejorar y reemplazar este if, ya que puede crecer mucho debido a 
     * casos particulares de cada tipo de evento.
     */
    private function applyExtraData(Event $event, Order $order, $date, array $extraData)
    {
        if ($event instanceof SingleInstanceRequest)
        {
            $event->setProvider($extraData['provider']);
        } else if ($event instanceof MultiInstanceRequest)
        {
            $event->setRemoteInstance($extraData['provider']->getInstance());
            $event->setRemoteState($this->createState('created', $date, $order, $extraData['provider']->getInstance()));
        }
    }

    private function setEventData(Event $event, Order $order, $state, $date, array $extraData)
    {
        $event->setDate($date);
        $event->setOperator($order->getOperator());
        $event->setInstance($order->getInstance());
        $event->setOrder($order);
        $event->setState($this->getState($state, $date, $order));

        $this->applyExtraData($event, $order, $date, $extraData);

        $this->dm->persist($event);
        $this->dm->flush();
    }

    private function getState($name, $date, Order $order, Instance $instance = null)
    {
        $currentState = $order->getCurrentState();

        $instance = is_null($instance) ? $order->getInstance() : $instance;

        if ($order->hasState($name))
        {
            $state = $order->getState($name);
        } else
        {
            if (!is_null($currentState))
            {
                $currentState->setIsCurrent(false);

                $this->dm->persist($currentState);
            }

            $state = $this->createState($name, $date, $order, $instance, $currentState);
            $order->setCurrentState($state);
            $this->dm->persist($order);
        }

        return $state;
    }

    private function createState($name, $date, Order $order, Instance $instance, State $currentState = null)
    {
        $state = new State();
        $state->setDate($date);
        $state->setInstance($instance);
        $state->setOrder($order);
        $state->setType(
                $this->dm->getRepository('CelsiusCelsius3Bundle:StateType')
                        ->createQueryBuilder()
                        ->field('name')->equals($name)
                        ->getQuery()
                        ->getSingleResult()
        );
        $state->setPrevious($currentState);

        $this->dm->persist($state);

        return $state;
    }

    /**
     * Receives the event name and the order document and creates the appropiate
     * event and state
     * 
     * @param string $name The event name
     * @param Celsius\Celsius3Bundle\Document\Order $order The Order document
     * @param array $extraData Extra data for the event
     */
    public function createEvent($name, Order $order, array $extraData = array())
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
        $this->setEventData(new $eventClassName, $order, $stateName, $date, $extraData);
    }

}