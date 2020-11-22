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

declare(strict_types=1);

namespace Celsius3\CoreBundle\Helper;

use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\Event\ApproveEvent;
use Celsius3\CoreBundle\Entity\Event\CreationEvent;
use Celsius3\CoreBundle\Entity\Event\Event;
use Celsius3\CoreBundle\Entity\Event\SearchEvent;
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

use function array_key_exists;
use function count;

class LifecycleHelper
{
    private $entityManager;
    private $stateManager;
    private $eventManager;
    private $fileManager;
    private $instanceHelper;
    private $securityTokenStorage;
    private $logger;

    public function __construct(
        EntityManager $entityManager,
        StateManager $stateManager,
        EventManager $eventManager,
        FileManager $fileManager,
        InstanceHelper $instanceHelper,
        TokenStorage $securityTokenStorage,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->stateManager = $stateManager;
        $this->eventManager = $eventManager;
        $this->fileManager = $fileManager;
        $this->instanceHelper = $instanceHelper;
        $this->securityTokenStorage = $securityTokenStorage;
        $this->logger = $logger;
    }

    public function getEventManager(): EventManager
    {
        return $this->eventManager;
    }

    public function copyFilesToPreviousRequest(Request $previousRequest, Request $request, Event $event): void
    {
        $this->fileManager->copyFilesToPreviousRequest($previousRequest, $request, $event);
    }

    /**
     * Receives the event name and the request document and creates the appropiate
     * event and state.
     */
    public function createEvent(string $name, Request $request, Instance $instance = null)
    {
        $this->entityManager->getConnection()->beginTransaction();
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

            $this->entityManager->persist($request);
            $this->entityManager->persist($event);
            $this->entityManager->flush();

            $this->entityManager->getConnection()->commit();

            return $event;
        } catch (\Exception $ex) {
            $this->entityManager->getConnection()->rollBack();
            $this->logger->error($ex->getMessage());
            $this->logger->error($ex->getTraceAsString());

            return null;
        }
    }

    private function preValidate($name, Request $request, Instance $instance = null): array
    {
        $session_instance = $this->instanceHelper->getSessionInstance();
        $request_instance = $request->getInstance();

        $instance = $instance ?? ($name !== EventManager::EVENT__CREATION ? $session_instance : $request_instance);
        $extra_data = $this->eventManager->prepareExtraData($name, $request, $instance);
        $event_name = $this->eventManager->getRealEventName($name, $extra_data, $instance, $request);
        $data = [
            'eventName' => $event_name,
            'stateName' => $this->stateManager->getStateForEvent($event_name),
            'instance' => $instance,
            'date' => date('Y-m-d H:i:s'),
            'extraData' => $extra_data,
            'eventClassName' => $this->eventManager->getFullClassNameForEvent($event_name),
        ];

        if ($name === EventManager::EVENT__RECEIVE) {
            $events = array_filter(
                $this->eventManager->getEvents(EventManager::EVENT__RECEIVE, $request->getId()),
                static function (Event $item) use ($extra_data) {
                    return $item->getRequestEvent()->getId() === $extra_data['request']->getId();
                }
            );

            if (count($events) > 0) {
                $data['event'] = array_pop($events);
            }
        }

        if ($name === EventManager::EVENT__UPLOAD) {
            $events = $this->eventManager->getEvents(EventManager::EVENT__UPLOAD, $request->getId());

            if (count($events) > 0) {
                $data['event'] = array_pop($events);
            }
        }

        if ($name === EventManager::EVENT__SEARCH) {
            $events = array_filter(
                $this->eventManager->getEvents(EventManager::EVENT__SEARCH, $request->getId()),
                static function (Event $item) use ($extra_data) {
                    return $item->getCatalog()->getId() === $extra_data['catalog']->getId();
                }
            );

            if (count($events) > 0) {
                $data['event'] = array_pop($events);
            }
        }

        if ($name !== EventManager::EVENT__CREATION && !$request->hasState(
                $this->stateManager->getPreviousMandatoryStates($data['stateName'])
            )) {
            throw Exception::create(Exception::PREVIOUS_STATE_NOT_FOUND);
        }

        return $data;
    }

    public function uploadFiles(Request $request, Event $event, array $files): void
    {
        $this->fileManager->uploadFiles($request, $event, $files);
    }

    private function moveCurrentState(Request $request, $stateName): void
    {
        $current_state = $request->getCurrentState();
        $state = $request->getState($stateName);
        if ($this->stateManager->isBefore($current_state, $state)) {
            $current_state->setCurrent(false);
            $state->setCurrent(true);
            $this->entityManager->persist($current_state);
            $this->entityManager->persist($state);
        }
    }

    private function setEventData(Request $request, array $data): Event
    {
        /** @var Event $event */
        $event = new $data['eventClassName']();
        $event->setOperator($this->securityTokenStorage->getToken()->getUser());
        $event->setInstance($data['instance']);
        $event->setRequest($request);
        $event->setState($this->getState($request, $data));

        $event->applyExtraData($request, $data, $this, $data['date']);
        $this->entityManager->persist($event->getState());
        $this->entityManager->persist($event);

        return $event;
    }

    public function getState(Request $request, array $data, Event $remoteEvent = null)
    {
        $instance = $data['instance'] ?? $request->getInstance();

        $current_state = $request->getCurrentState();

        if ($request->hasState($data['stateName'])) {
            $state = $request->getState($data['stateName']);
            $state->setRemoteEvent($remoteEvent);
            if ($current_state !== null && $this->stateManager->isBefore($current_state, $state)) {
                $current_state->setCurrent(false);
                $state->setCurrent(true);
                $this->entityManager->persist($current_state);

                if ($data['eventName'] === EventManager::EVENT__LOCAL_CANCEL
                    || $data['eventName'] === EventManager::EVENT__REMOTE_CANCEL) {
                    $this->entityManager->persist($state);
                    $this->entityManager->flush();
                    $this->refresh($request);
                }
            }
        } else {
            if ($current_state !== null) {
                $current_state->setCurrent(false);
                $this->entityManager->persist($current_state);
                $this->entityManager->flush($current_state);
            }
            $state = $this->createState($request, $instance, $data, $current_state, $remoteEvent);
            $this->entityManager->persist($state);
            $this->entityManager->flush($state);
        }

        return $state;
    }

    public function refresh($entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        $this->entityManager->refresh($entity);
    }

    private function createState(
        Request $request,
        Instance $instance,
        array $data,
        State $currentState = null,
        Event $remoteEvent = null
    ): State {
        $state = new State();
        $state->setInstance($instance);
        $state->setRequest($request);
        $state->setType($data['stateName']);
        $state->setPrevious($currentState);
        $state->setRemoteEvent($remoteEvent);
        $state->setCurrent(true);

        return $state;
    }

    public function createRequest(Order $order, BaseUser $user, $type, Instance $instance, BaseUser $creator)
    {
        if ($order->hasRequest($instance)) {
            $request = $order->getRequest($instance);
        } else {
            $request = new Request();
            $request->setOwner($user);
            $request->setType($type);
            $request->setInstance($instance);
            $request->setOrder($order);
            $request->setCreator($creator);
        }

        return $request;
    }

    public function undoState(Request $request): ?UndoEvent
    {
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $current_state = $request->getCurrentState();
            if ($previous_state = $current_state->getPrevious()) {
                $current_state->setCurrent(false);
                $previous_state->setCurrent(true);

                $event = new UndoEvent();
                $event->setRequest($request);
                $event->setOperator($this->securityTokenStorage->getToken()->getUser());
                $event->setInstance($request->getInstance());
                $event->setState($previous_state);
                $previous_state->addEvent($event);

                $this->stateManager->extraUndoActions($current_state);

                $this->refresh($event);
                $this->refresh($current_state);

                $this->entityManager->getConnection()->commit();

                return $event;
            }
        } catch (\Exception $ex) {
            $this->entityManager->getConnection()->rollBack();
            $this->logger->error($ex->getMessage());
            $this->logger->error($ex->getTraceAsString());
        }

        return null;
    }

    public function createRequestEvent(Request $request, Instance $instance = null)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $data = $this->preValidateRequestEvent($request, $instance);

            $event = $data['event'] ?? $this->setEventData($request, $data);

            $this->entityManager->persist($request);
            $this->entityManager->persist($event);
            $this->entityManager->flush();

            $this->entityManager->getConnection()->commit();

            return $event;
        } catch (\Exception $ex) {
            $this->entityManager->getConnection()->rollBack();
            $this->logger->error($ex->getMessage());
            $this->logger->error($ex->getTraceAsString());

            return null;
        }
    }

    public function createCreationEvent(Request $request, ?Instance $instance)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $data = $this->preValidateCreationEvent($request, $instance);
            $creation_event = $data['event'] ?? $this->setEventData($request, $data);

            $this->entityManager->persist($request);
            $this->entityManager->persist($creation_event);
            $this->entityManager->flush();

            $this->entityManager->getConnection()->commit();

            return $creation_event;
        } catch (\Exception $ex) {
            $this->entityManager->getConnection()->rollBack();
            $this->logger->error($ex->getMessage());
            $this->logger->error($ex->getTraceAsString());

            return null;
        }
    }

    public function createApproveEvent(Request $request, ?Instance $instance)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $data = $this->preValidateApproveEvent($request, $instance);
            $event = $data['event'] ?? $this->setEventData($request, $data);

            $this->entityManager->persist($request);
            $this->entityManager->persist($event);
            $this->entityManager->flush();

            $this->entityManager->getConnection()->commit();

            return $event;
        } catch (\Exception $ex) {
            $this->entityManager->getConnection()->rollBack();
            $this->logger->error($ex->getMessage());
            $this->logger->error($ex->getTraceAsString());

            return null;
        }
    }

    private function preValidateCreationEvent(Request $request, ?Instance $instance): array
    {
        return [
            'eventName' => 'creation',
            'stateName' => StateManager::STATE__CREATED,
            'instance' => $instance ?? $request->getInstance(),
            'date' => date('Y-m-d H:i:s'),
            'extraData' => [],
            'eventClassName' => CreationEvent::class,
        ];
    }

    public function createSearchEvent(Request $request, ?Instance $instance)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $data = $this->preValidateSearchEvent($request, $instance);
            if (array_key_exists('event', $data)) {
                $event = $data['event'];
                $event->setResult($data['extraData']['result']);
                $this->moveCurrentState($request, StateManager::STATE__SEARCHED);
            } else {
                $event = $this->setEventData($request, $data);
            }

            $this->entityManager->persist($request);
            $this->entityManager->persist($event);
            $this->entityManager->flush();

            $this->entityManager->getConnection()->commit();

            return $event;
        } catch (\Exception $ex) {
            $this->entityManager->getConnection()->rollBack();
            $this->logger->error($ex->getMessage());
            $this->logger->error($ex->getTraceAsString());

            return null;
        }
    }

    private function preValidateSearchEvent(Request $request, ?Instance $instance): array
    {
        $session_instance = $this->instanceHelper->getSessionInstance();
        $extra_data = $this->eventManager->prepareExtraDataForSearch();

        $data = [
            'eventName' => EventManager::EVENT__SEARCH,
            'stateName' => $this->stateManager->getStateForEvent(EventManager::EVENT__SEARCH),
            'instance' => $instance ?? $session_instance,
            'date' => date('Y-m-d H:i:s'),
            'extraData' => $extra_data,
            'eventClassName' => SearchEvent::class,
        ];

        $events = array_filter(
            $this->eventManager->getEvents(EventManager::EVENT__SEARCH, $request->getId()),
            static function (Event $item) use ($extra_data) {
                return $item->getCatalog()->getId() === $extra_data['catalog']->getId();
            }
        );

        if (count($events) > 0) {
            $data['event'] = array_pop($events);
        }


        if (!$request->hasState($this->stateManager->getPreviousMandatoryStates($data['stateName']))) {
            throw Exception::create(Exception::PREVIOUS_STATE_NOT_FOUND);
        }

        return $data;
    }

    private function preValidateRequestEvent(Request $request, Instance $instance = null): array
    {
        $session_instance = $this->instanceHelper->getSessionInstance();

        $instance = $instance ?? $session_instance;
        $extra_data = $this->eventManager->prepareExtraDataForRequest();
        $event_name = $this->eventManager->getRealRequestEventName($extra_data, $instance, $request);
        $data = [
            'eventName' => $event_name,
            'stateName' => $this->stateManager->getStateForEvent($event_name),
            'instance' => $instance,
            'date' => date('Y-m-d H:i:s'),
            'extraData' => $extra_data,
            'eventClassName' => $this->eventManager->getFullClassNameForEvent($event_name),
        ];

        if (!$request->hasState(
            $this->stateManager->getPreviousMandatoryStates($data['stateName'])
        )) {
            throw Exception::create(Exception::PREVIOUS_STATE_NOT_FOUND);
        }

        return $data;
    }

    private function preValidateApproveEvent(Request $request, Instance $instance = null): array
    {
        $session_instance = $this->instanceHelper->getSessionInstance();

        $data = [
            'eventName' => EventManager::EVENT__APPROVE,
            'stateName' => $this->stateManager->getStateForEvent(EventManager::EVENT__APPROVE),
            'instance' => $instance ?? $session_instance,
            'date' => date('Y-m-d H:i:s'),
            'extraData' => $this->eventManager->prepareExtraDataForApprove(),
            'eventClassName' => ApproveEvent::class
        ];

        if (!$request->hasState($this->stateManager->getPreviousMandatoryStates($data['stateName']))) {
            throw Exception::create(Exception::PREVIOUS_STATE_NOT_FOUND);
        }

        return $data;
    }
}
