<?php

namespace Celsius\Celsius3Bundle\Helper;

use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Document\State;
use Celsius\Celsius3Bundle\Document\Event;
use Celsius\Celsius3Bundle\Document\MultiInstanceRequest;
use Celsius\Celsius3Bundle\Document\SingleInstanceRequest;
use Celsius\Celsius3Bundle\Document\Instance;
use Celsius\Celsius3Bundle\Document\Receive;
use Celsius\Celsius3Bundle\Helper\InstanceHelper;
use Celsius\Celsius3Bundle\Manager\StateManager;
use Celsius\Celsius3Bundle\Manager\EventManager;
use Celsius\Celsius3Bundle\Manager\FileManager;

class LifecycleHelper
{

    private $dm;
    private $state_manager;
    private $event_manager;
    private $file_manager;
    private $instance_helper;

    public function __construct(DocumentManager $dm, StateManager $state_manager, EventManager $event_manager, FileManager $file_manager, InstanceHelper $instance_helper)
    {
        $this->dm = $dm;
        $this->state_manager = $state_manager;
        $this->event_manager = $event_manager;
        $this->file_manager = $file_manager;
        $this->instance_helper = $instance_helper;
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
            $event->setProvider($extraData['provider']);
            $event->setRemoteInstance($extraData['provider']->getCelsiusInstance());
            $event->setRemoteState($this->createState('created', $date, $order, $extraData['provider']->getCelsiusInstance()));
        } else if ($event instanceof Receive)
        {
            $requestEvent = $this->dm->find('CelsiusCelsius3Bundle:Event', $extraData['request']);
            $event->setRequestEvent($requestEvent);

            $this->file_manager->uploadFiles($order, $event, $extraData['files']);
        }
    }

    private function setEventData(Event $event, Order $order, $state, $date, array $extraData, Instance $instance)
    {
        $event->setDate($date);
        $event->setOperator($order->getOperator());
        $event->setInstance($instance);
        $event->setOrder($order);

        $state = $this->getState($state, $date, $order, $event, $instance);

        $event->setState($state);

        $this->dm->persist($event);
        $this->dm->flush();

        $this->applyExtraData($event, $order, $date, $extraData);

        $this->dm->persist($event);
        $this->dm->persist($state);
        $this->dm->flush();
    }

    private function getState($name, $date, Order $order, Event $event, Instance $instance)
    {
        $currentState = $order->getCurrentState($instance);

        $instance = is_null($instance) ? $order->getInstance() : $instance;

        if ($order->hasState($name, $instance))
        {
            $state = $order->getState($name, $instance);
        } else
        {
            if (!is_null($currentState))
            {
                $currentState->setIsCurrent(false);

                $this->dm->persist($currentState);
            }

            $state = $this->createState($name, $date, $order, $instance, $currentState);
        }
        $state->addEvents($event);

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
                        ->findOneBy(array('name' => $name))
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
        $stateName = $this->state_manager->getStateForEvent($name);
        $instance = $name != EventManager::EVENT__CREATION ? $this->instance_helper->getSessionInstance() : $order->getInstance();

        if (!$order->hasState($this->state_manager->getPreviousMandatoryState($stateName), $instance) && $name != EventManager::EVENT__CREATION)
        {
            throw $this->state_manager->createNotFoundException('State not found');
        }

        $date = date('Y-m-d H:i:s');

        $orderDateMethod = 'set' . ucfirst($stateName);
        $eventClassName = $this->event_manager->getFullClassNameForEvent($name);

        $order->$orderDateMethod($date);
        $this->setEventData(new $eventClassName, $order, $stateName, $date, $extraData, $instance);
    }

    public function reclaim(Receive $event, Order $order)
    {
        $date = date('Y-m-d H:i:s');

        $request = $event->getRequestEvent();
        $extraData = array(
            'provider' => $request->getProvider(),
        );
        $eventClassName = $this->dm->getClassMetadata(get_class($request))->name;

        $this->setEventData(new $eventClassName, $order, 'requested', $date, $extraData);

        $event->setReclaimed(true);
        $this->dm->persist($event);
        $this->dm->flush();
    }

}