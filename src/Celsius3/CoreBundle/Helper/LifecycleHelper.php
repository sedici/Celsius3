<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\CoreBundle\Helper;

use Doctrine\ORM\EntityManager;
use Celsius3\CoreBundle\Entity\Order;
use Celsius3\CoreBundle\Entity\Request;
use Celsius3\CoreBundle\Entity\State;
use Celsius3\CoreBundle\Entity\Event\Event;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Helper\InstanceHelper;
use Celsius3\CoreBundle\Manager\EventManager;
use Celsius3\CoreBundle\Manager\FileManager;
use Celsius3\CoreBundle\Manager\StateManager;
use Celsius3\CoreBundle\Exception\PreviousStateNotFoundException;
use Celsius3\CoreBundle\Entity\Event\UndoEvent;

class LifecycleHelper
{
    private $em;
    private $state_manager;
    private $event_manager;
    private $file_manager;
    private $instance_helper;
    private $container;

    public function __construct(EntityManager $em, StateManager $state_manager, EventManager $event_manager, FileManager $file_manager, InstanceHelper $instance_helper)
    {
        $this->em = $em;
        $this->state_manager = $state_manager;
        $this->event_manager = $event_manager;
        $this->file_manager = $file_manager;
        $this->instance_helper = $instance_helper;
    }

    public function getEventManager()
    {
        return $this->event_manager;
    }

    public function refresh($entity)
    {
        $this->em->persist($entity);
        $this->em->flush($entity);
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
        $this->em->persist($event);

        return $event;
    }

    public function getState(Request $request, Event $event, array $data, Event $remoteEvent = null)
    {
        $instance = is_null($data['instance']) ? $request->getInstance() : $data['instance'];

        $currentState = $request->getCurrentState();

        if ($request->hasState($data['stateName'])) {
            $state = $request->getState($data['stateName']);
            $state->setRemoteEvent($remoteEvent);
            if ($this->state_manager->isBefore($currentState, $state)) {
                $state->setIsCurrent(true);
                $currentState->setIsCurrent(false);
                $this->em->persist($currentState);

                if ($data['eventName'] === EventManager::EVENT__LOCAL_CANCEL || $data['eventName'] === EventManager::EVENT__REMOTE_CANCEL) {
                    $this->em->persist($state);
                    $this->em->flush();
                    $this->em->refresh($request);
                }
            }
        } else {
            if (!is_null($currentState)) {
                $currentState->setIsCurrent(false);
                $this->em->persist($currentState);
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
        $state->setType($data['stateName']);
        $state->setPrevious($currentState);
        $state->setRemoteEvent($remoteEvent);
        $state->setIsCurrent(true);

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

        /**
         * @todo Refactorizar estos tres ifs
         */
        if ($name === EventManager::EVENT__RECEIVE) {
            $events = array_filter($this->event_manager->getEvents(EventManager::EVENT__RECEIVE, $request->getId()), function ($item) use ($extraData) {
                return $item->getRequestEvent()->getId() === $extraData['request']->getId();
            });

            if (count($events) > 0) {
                $data['event'] = array_pop($events);
            }
        }

        if ($name === EventManager::EVENT__UPLOAD) {
            $events = $this->event_manager->getEvents(EventManager::EVENT__UPLOAD, $request->getId());

            if (count($events) > 0) {
                $data['event'] = array_pop($events);
            }
        }

        if ($name === EventManager::EVENT__SEARCH) {
            $events = array_filter($this->event_manager->getEvents(EventManager::EVENT__SEARCH, $request->getId()), function ($item) use ($extraData) {
                return $item->getCatalog()->getId() === $extraData['catalog']->getId();
            });

            if (count($events) > 0) {
                $data['event'] = array_pop($events);
            }
        }

        if (!$request->hasState($this->state_manager->getPreviousMandatoryStates($data['stateName']), $data['instance']) && $name != EventManager::EVENT__CREATION) {
            throw new PreviousStateNotFoundException('State not found');
        }

        return $data;
    }

    /**
     * Receives the event name and the request document and creates the appropiate
     * event and state
     *
     * @param string                                $name     The event name
     * @param Celsius3\CoreBundle\Entity\Request  $request  The Request document
     * @param Celsius3\CoreBundle\Entity\Instance $instance The Instance document
     */
    public function createEvent($name, Request $request, Instance $instance = null)
    {
        try {
            $data = $this->preValidate($name, $request, $instance);
            if (array_key_exists('event', $data)) {
                /**
                 * @todo Refactorizar esta rama del if
                 */
                $event = $data['event'];
                if ($name === EventManager::EVENT__RECEIVE || $name === EventManager::EVENT__UPLOAD) {
                    $this->uploadFiles($request, $event, $data['extraData']['files']);
                } elseif ($name === EventManager::EVENT__SEARCH) {
                    $event->setResult($data['extraData']['result']);
                    $currentState = $request->getCurrentState();
                    $state = $request->getState(StateManager::STATE__SEARCHED);
                    if ($this->state_manager->isBefore($currentState, $state)) {
                        $currentState->setIsCurrent(false);
                        $state->setIsCurrent(true);
                        $this->em->persist($currentState);
                        $this->em->persist($state);
                    }
                }
            } else {
                $event = $this->setEventData($request, $data);
            }
            $this->refresh($request);
            $this->em->refresh($event);

            return $event;
        } catch (PreviousStateNotFoundException $e) {
            return null;
        }
    }

    public function createRequest(Order $order, BaseUser $user, $type, Instance $instance, BaseUser $creator)
    {
        $request = new Request();
        $request->setOwner($user);
        $request->setType($type);
        $request->setInstance($instance);
        $request->setOrder($order);
        $request->setCreator($creator);
        $this->em->persist($request);

        return $request;
    }

    public function undoState(Request $request)
    {
        $currentState = $request->getCurrentState();
        if ($previousState = $currentState->getPrevious()) {
            $currentState->setIsCurrent(false);
            $previousState->setIsCurrent(true);

            $event = new UndoEvent();
            $event->setDate(date('Y-m-d H:i:s'));
            $event->setRequest($request);
            $event->setOperator($this->container->get('security.context')->getToken()->getUser());
            $event->setInstance($request->getInstance());
            $event->setState($previousState);
            $previousState->addEvent($event);

            $this->state_manager->extraUndoActions($currentState);

            $this->refresh($event);
            $this->em->refresh($event);
            $this->refresh($currentState);

            return $event;
        } else {
            return null;
        }
    }
}
