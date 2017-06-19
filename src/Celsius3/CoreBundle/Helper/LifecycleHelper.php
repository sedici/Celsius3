<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
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

use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\Event\Event;
use Celsius3\CoreBundle\Entity\Event\UndoEvent;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Entity\Order;
use Celsius3\CoreBundle\Entity\Request;
use Celsius3\CoreBundle\Entity\State;
use Celsius3\CoreBundle\Exception\Exception;
use Celsius3\CoreBundle\Manager\EventManager;
use Celsius3\CoreBundle\Manager\FileManager;
use Celsius3\CoreBundle\Manager\StateManager;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class LifecycleHelper
{
    private $em;
    private $state_manager;
    private $event_manager;
    private $file_manager;
    private $instance_helper;
    private $security_token_storage;
    private $logger;

    public function __construct(EntityManager $em, StateManager $state_manager, EventManager $event_manager, FileManager $file_manager, InstanceHelper $instance_helper, TokenStorage $security_token_storage, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->state_manager = $state_manager;
        $this->event_manager = $event_manager;
        $this->file_manager = $file_manager;
        $this->instance_helper = $instance_helper;
        $this->security_token_storage = $security_token_storage;
        $this->logger = $logger;
    }

    public function getEventManager()
    {
        return $this->event_manager;
    }

    public function refresh($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
        $this->em->refresh($entity);
    }

    public function uploadFiles(Request $request, Event $event, array $files)
    {
        $this->file_manager->uploadFiles($request, $event, $files);
    }

    public function copyFilesToPreviousRequest(Request $previousRequest, Request $request, Event $event)
    {
        $this->file_manager->copyFilesToPreviousRequest($previousRequest, $request, $event);
    }

    private function setEventData(Request $request, array $data)
    {
        /** @var $event Event */
        $event = new $data['eventClassName']();
        $event->setOperator($this->security_token_storage->getToken()->getUser());
        $event->setInstance($data['instance']);
        $event->setRequest($request);
        $event->setState($this->getState($request, $data));

        $event->applyExtraData($request, $data, $this, $data['date']);
        $this->em->persist($event->getState());
        $this->em->persist($event);

        return $event;
    }

    public function getState(Request $request, array $data, Event $remoteEvent = null)
    {
        $instance = is_null($data['instance']) ? $request->getInstance() : $data['instance'];

        $currentState = $request->getCurrentState();

        if ($request->hasState($data['stateName'])) {
            $state = $request->getState($data['stateName']);
            $state->setRemoteEvent($remoteEvent);
            if (!is_null($currentState) && $this->state_manager->isBefore($currentState, $state)) {
                $currentState->setCurrent(false);
                $state->setCurrent(true);
                $this->em->persist($currentState);

                if ($data['eventName'] === EventManager::EVENT__LOCAL_CANCEL || $data['eventName'] === EventManager::EVENT__REMOTE_CANCEL) {
                    $this->em->persist($state);
                    $this->em->flush();
                    $this->refresh($request);
                }
            }
        } else {
            if (!is_null($currentState)) {
                $currentState->setCurrent(false);
                $this->em->persist($currentState);
                $this->em->flush($currentState);
            }
            $state = $this->createState($request, $instance, $data, $currentState, $remoteEvent);
            $this->em->persist($state);
            $this->em->flush($state);
        }

        return $state;
    }

    private function createState(Request $request, Instance $instance, array $data, State $currentState = null, Event $remoteEvent = null)
    {
        $state = new State();
        $state->setInstance($instance);
        $state->setRequest($request);
        $state->setType($data['stateName']);
        $state->setPrevious($currentState);
        $state->setRemoteEvent($remoteEvent);
        $state->setCurrent(true);

        return $state;
    }

    private function preValidate($name, Request $request, Instance $instance = null)
    {
        $instance = is_null($instance) ? ($name != EventManager::EVENT__CREATION ? $this->instance_helper->getSessionInstance() : $request->getInstance()) : $instance;
        $extraData = $this->event_manager->prepareExtraData($name, $request, $instance);
        $eventName = $this->event_manager->getRealEventName($name, $extraData, $instance, $request);
        $data = array(
            'eventName' => $eventName,
            'stateName' => $this->state_manager->getStateForEvent($eventName),
            'instance' => $instance,
            'date' => date('Y-m-d H:i:s'),
            'extraData' => $extraData,
            'eventClassName' => $this->event_manager->getFullClassNameForEvent($eventName),
        );

        if ($name === EventManager::EVENT__RECEIVE) {
            $events = array_filter($this->event_manager->getEvents(EventManager::EVENT__RECEIVE, $request->getId()), function (Event $item) use ($extraData) {
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
            $events = array_filter($this->event_manager->getEvents(EventManager::EVENT__SEARCH, $request->getId()), function (Event $item) use ($extraData) {
                return $item->getCatalog()->getId() === $extraData['catalog']->getId();
            });

            if (count($events) > 0) {
                $data['event'] = array_pop($events);
            }
        }

        if (!$request->hasState($this->state_manager->getPreviousMandatoryStates($data['stateName']), $data['instance']) && $name !== EventManager::EVENT__CREATION) {
            throw Exception::create(Exception::PREVIOUS_STATE_NOT_FOUND);
        }

        return $data;
    }

    private function moveCurrentState(Request $request, $stateName)
    {
        $currentState = $request->getCurrentState();
        $state = $request->getState($stateName);
        if ($this->state_manager->isBefore($currentState, $state)) {
            $currentState->setCurrent(false);
            $state->setCurrent(true);
            $this->em->persist($currentState);
            $this->em->persist($state);
        }
    }

    /**
     * Receives the event name and the request document and creates the appropiate
     * event and state.
     *
     * @param string   $name     The event name
     * @param Request  $request  The Request document
     * @param Instance $instance The Instance document
     */
    public function createEvent($name, Request $request, Instance $instance = null)
    {
        $this->em->getConnection()->beginTransaction();
        try {
            $data = $this->preValidate($name, $request, $instance);
            if (array_key_exists('event', $data)) {
                $event = $data['event'];
                if ($name === EventManager::EVENT__RECEIVE || $name === EventManager::EVENT__UPLOAD) {
                    $this->uploadFiles($request, $event, $data['extraData']['files']);
                    $event->setReclaimed(false);
                    $this->moveCurrentState($request, StateManager::STATE__RECEIVED);
                } elseif ($name === EventManager::EVENT__SEARCH) {
                    $event->setResult($data['extraData']['result']);
                    $this->moveCurrentState($request, StateManager::STATE__SEARCHED);
                }
            } else {
                $event = $this->setEventData($request, $data);
            }

            $this->em->persist($request);
            $this->em->persist($event);
            $this->em->flush();

            $this->em->getConnection()->commit();

            return $event;
        } catch (Exception $ex) {
            $this->em->getConnection()->rollback();
            $this->logger->error($ex->getMessage());
            $this->logger->error($ex->getTraceAsString());

            return null;
        }
    }

    public function createRequest(Order $order, BaseUser $user, $type, Instance $instance, BaseUser $creator)
    {
        // Si no existe el request para la instancia, se crea
        if (!$order->hasRequest($instance)) {
            $request = new Request();
            $request->setOwner($user);
            $request->setType($type);
            $request->setInstance($instance);
            $request->setOrder($order);
            $request->setCreator($creator);
        } else {
            $request = $order->getRequest($instance);
        }

        return $request;
    }

    public function undoState(Request $request)
    {
        $this->em->getConnection()->beginTransaction();
        try {
            $currentState = $request->getCurrentState();
            if ($previousState = $currentState->getPrevious()) {
                $currentState->setCurrent(false);
                $previousState->setCurrent(true);

                $event = new UndoEvent();
                $event->setRequest($request);
                $event->setOperator($this->security_token_storage->getToken()->getUser());
                $event->setInstance($request->getInstance());
                $event->setState($previousState);
                $previousState->addEvent($event);

                $this->state_manager->extraUndoActions($currentState);

                $this->refresh($event);
                $this->refresh($currentState);

                $this->em->getConnection()->commit();

                return $event;
            } else {
                return null;
            }
        } catch (Exception $ex) {
            $this->em->getConnection()->rollback();
            $this->logger->error($ex->getMessage());
            $this->logger->error($ex->getTraceAsString());

            return null;
        }
    }
}
