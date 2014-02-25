<?php

namespace Celsius3\CoreBundle\Helper;

use Celsius3\CoreBundle\Document\Order;
use Celsius3\CoreBundle\Document\Request;
use Celsius3\CoreBundle\Document\State;
use Celsius3\CoreBundle\Document\Event\Event;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Manager\EventManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius3\CoreBundle\Exception\PreviousStateNotFoundException;
use Celsius3\CoreBundle\Document\Event\UndoEvent;

class LifecycleHelper
{

    private $dm;
    private $state_manager;
    private $event_manager;
    private $file_manager;
    private $instance_helper;
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->dm = $this->container
                ->get('doctrine.odm.mongodb.document_manager');
        $this->state_manager = $this->container->get('celsius3_core.state_manager');
        $this->event_manager = $this->container->get('celsius3_core.event_manager');
        $this->file_manager = $this->container->get('celsius3_core.file_manager');
        $this->instance_helper = $this->container->get('celsius3_core.instance_helper');
    }

    public function getEventManager()
    {
        return $this->event_manager;
    }

    public function refresh($document)
    {
        $this->dm->persist($document);
        $this->dm->flush();
    }

    public function uploadFiles(Request $request, Event $event, array $files)
    {
        $this->file_manager->uploadFiles($request, $event, $files);
    }

    private function setEventData(Request $request, array $data)
    {
        /* @var $event Event */
        $event = new $data['eventClassName'];
        $event->setDate($data['date']);
        $event->setOperator($request->getOperator());
        $event->setInstance($data['instance']);
        $event->setRequest($request);
        $event->setState($this->getState($request, $event, $data));
        $event->applyExtraData($request, $data, $this, $data['date']);
        $this->dm->persist($event);
    }

    public function getState(Request $request, Event $event, array $data, Event $remoteEvent = null)
    {
        $instance = is_null($data['instance']) ? $request->getInstance() : $data['instance'];

        $currentState = $request->getCurrentState();

        if ($request->hasState($data['stateName'])) {
            $state = $request->getState($data['stateName']);
            $state->setRemoteEvent($remoteEvent);
        } else {
            if (!is_null($currentState)) {
                $currentState->setIsCurrent(false);
                $this->dm->persist($currentState);
            }
            $state = $this->createState($request, $instance, $data, $currentState, $remoteEvent);
        }
        $state->addEvent($event);

        return $state;
    }

    private function createState(Request $request, Instance $instance, array $data, State $currentState = null, Event $remoteEvent = null)
    {
        $state = new State();
        $state->setDate($data['date']);
        $state->setInstance($instance);
        $state->setRequest($request);
        $state->setType($this->dm->getRepository('Celsius3CoreBundle:StateType')->findOneBy(array('name' => $data['stateName'])));
        $state->setPrevious($currentState);
        $state->setRemoteEvent($remoteEvent);

        return $state;
    }

    private function preValidate($name, Request $request, Instance $instance = null)
    {
        $instance = is_null($instance) ? ($name != EventManager::EVENT__CREATION ? $this->instance_helper->getSessionInstance() : $request->getInstance()) : $instance;
        $extraData = $this->event_manager->prepareExtraData($name, $request, $instance);
        $eventName = $this->event_manager->getRealEventName($name, $extraData);
        $data = array(
            'eventName' => $eventName,
            'stateName' => $this->state_manager->getStateForEvent($eventName),
            'instance' => $instance,
            'date' => date('Y-m-d H:i:s'),
            'extraData' => $extraData,
            'orderDateMethod' => 'set' . ucfirst($this->state_manager->getStateForEvent($eventName)),
            'eventClassName' => $this->event_manager->getFullClassNameForEvent($eventName),
        );

        if (!$request->hasState($this->state_manager->getPreviousMandatoryState($data['stateName']), $data['instance']) && $name != EventManager::EVENT__CREATION) {
            throw new PreviousStateNotFoundException('State not found');
        }

        return $data;
    }

    /**
     * Receives the event name and the order document and creates the appropiate
     * event and state
     *
     * @param string $name The event name
     * @param Celsius3\CoreBundle\Document\Order $order The Order document
     */
    public function createEvent($name, Request $request, Instance $instance = null)
    {
        try {
            $data = $this->preValidate($name, $request, $instance);
            $this->setEventData($request, $data);
            $this->refresh($request);
            return true;
        } catch (PreviousStateNotFoundException $e) {
            return false;
        }
    }

    public function undoState(Order $order)
    {
        $instance = $this->instance_helper->getSessionInstance();
        $currentState = $order->getCurrentState($instance);
        if (!is_null($currentState->getPrevious())) {
            $previousState = $currentState->getPrevious();
            $currentState->setIsCurrent(false);
            $previousState->setIsCurrent(true);

            $event = new UndoEvent();
            $event->setDate(date('Y-m-d H:i:s'));
            $event->setOrder($order);
            $event
                    ->setOperator(
                            $this->container->get('security.context')
                            ->getToken()->getUser());
            $event->setInstance($instance);
            $event->setState($previousState);
            $previousState->addEvent($event);

            $this->state_manager->extraUndoActions($currentState);

            $this->refresh($event);
            $this->refresh($currentState);
            return true;
        } else {
            return false;
        }
    }

}
