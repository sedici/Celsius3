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

namespace Celsius3\Manager;

use Symfony\Component\HttpFoundation\RequestStack;
use Celsius3\Exception\NotFoundException;
use Celsius3\Entity\State;
use Celsius3\Exception\Exception;

class StateManager
{

    public const STATE__CREATED = 'created';
    public const STATE__SEARCHED = 'searched';
    public const STATE__REQUESTED = 'requested';
    public const STATE__APPROVAL_PENDING = 'approval_pending';
    public const STATE__RECEIVED = 'received';
    public const STATE__DELIVERED = 'delivered';
    public const STATE__CANCELLED = 'cancelled';
    public const STATE__ANNULLED = 'annulled';
    public const STATE__TAKEN = 'taken';

    public static $stateTypes = [
        self::STATE__CREATED,
        self::STATE__SEARCHED,
        self::STATE__REQUESTED,
        self::STATE__RECEIVED,
        self::STATE__CANCELLED,
        self::STATE__ANNULLED,
        self::STATE__DELIVERED,
        self::STATE__APPROVAL_PENDING,
        self::STATE__TAKEN,
    ];
    private $graph = [
        self::STATE__CREATED => [
            'positive' => true,
            'mandatory' => true,
            'events' => [
                EventManager::EVENT__SEARCH => [
                    'weight' => 10,
                    'destinationState' => self::STATE__SEARCHED,
                ],
                EventManager::EVENT__TAKE => [
                    'weight' => 9,
                    'destinationState' => self::STATE__TAKEN,
                ],
                EventManager::EVENT__CANCEL => [
                    //Por RemoteCancel
                    'weight' => 2,
                    'destinationState' => self::STATE__CANCELLED,
                ],
                EventManager::EVENT__ANNUL => [
                    'weight' => 1,
                    'destinationState' => self::STATE__ANNULLED,
                ],
            ],
            'previousStates' => [],
            'originatingEvents' => [
                EventManager::EVENT__CREATION,
            ],
        ],
        self::STATE__TAKEN => [
            'positive' => true,
            'mandatory' => true,
            'events' => [
                EventManager::EVENT__UPLOAD => [
                    'weight' => 10,
                    'destinationState' => self::STATE__RECEIVED,
                    'remoteState' => self::STATE__APPROVAL_PENDING,
                ],
            ],
            'previousStates' => [
                self::STATE__CREATED,
            ],
            'originatingEvents' => [
                EventManager::EVENT__TAKE,
            ],
        ],
        self::STATE__SEARCHED => [
            'positive' => true,
            'mandatory' => true,
            'events' => [
                EventManager::EVENT__MULTI_INSTANCE_REQUEST => [
                    'weight' => 10,
                    'destinationState' => self::STATE__REQUESTED,
                    'remoteState' => self::STATE__CREATED,
                ],
                EventManager::EVENT__SINGLE_INSTANCE_REQUEST => [
                    'weight' => 9,
                    'destinationState' => self::STATE__REQUESTED,
                ],
                EventManager::EVENT__CANCEL => [
                    'weight' => 2,
                    'destinationState' => self::STATE__CANCELLED,
                ],
                EventManager::EVENT__ANNUL => [
                    'weight' => 1,
                    'destinationState' => self::STATE__ANNULLED,
                ],
            ],
            'previousStates' => [
                self::STATE__CREATED,
            ],
            'originatingEvents' => [
                EventManager::EVENT__SEARCH,
            ],
        ],
        self::STATE__REQUESTED => [
            'positive' => true,
            'mandatory' => true,
            'events' => [
                EventManager::EVENT__SINGLE_INSTANCE_RECEIVE => [
                    'weight' => 10,
                    'destinationState' => self::STATE__RECEIVED,
                ],
                EventManager::EVENT__MULTI_INSTANCE_RECEIVE => [
                    'weight' => 9,
                    'destinationState' => self::STATE__RECEIVED,
                    'remoteState' => self::STATE__APPROVAL_PENDING,
                ],
                EventManager::EVENT__SEARCH_PENDINGS => [
                    'weight' => 8,
                    'destinationState' => self::STATE__REQUESTED,
                ],
                EventManager::EVENT__NO_SEARCH_PENDINGS => [
                    'weight' => 7,
                    'destinationState' => self::STATE__REQUESTED,
                ],
                EventManager::EVENT__CANCEL => [
                    'weight' => 3,
                    'destinationState' => self::STATE__CANCELLED,
                ],
                EventManager::EVENT__LOCAL_CANCEL => [
                    'weight' => 2,
                    'destinationState' => self::STATE__REQUESTED,
                ],
                EventManager::EVENT__REMOTE_CANCEL => [
                    'weight' => 1,
                    'destinationState' => self::STATE__REQUESTED,
                ],
            ],
            'previousStates' => [
                self::STATE__SEARCHED,
                self::STATE__APPROVAL_PENDING,
            ],
            'originatingEvents' => [
                EventManager::EVENT__MULTI_INSTANCE_REQUEST,
                EventManager::EVENT__SINGLE_INSTANCE_REQUEST,
                EventManager::EVENT__SEARCH_PENDINGS,
                EventManager::EVENT__NO_SEARCH_PENDINGS,
                EventManager::EVENT__RECLAIM,
                EventManager::EVENT__LOCAL_CANCEL,
                EventManager::EVENT__REMOTE_CANCEL,
            ],
        ],
        self::STATE__APPROVAL_PENDING => [
            'positive' => true,
            'mandatory' => false,
            'events' => [
                EventManager::EVENT__APPROVE => [
                    'weight' => 10,
                    'destinationState' => self::STATE__RECEIVED,
                ],
                EventManager::EVENT__RECLAIM => [
                    'weight' => 2,
                    'destinationState' => self::STATE__REQUESTED,
                ],
                EventManager::EVENT__CANCEL => [
                    'weight' => 1,
                    'destinationState' => self::STATE__CANCELLED,
                ],
            ],
            'previousStates' => [
                self::STATE__REQUESTED,
            ],
            'originatingEvents' => [
                EventManager::EVENT__MULTI_INSTANCE_RECEIVE,
                EventManager::EVENT__UPLOAD,
            ],
        ],
        self::STATE__RECEIVED => [
            'positive' => true,
            'mandatory' => true,
            'events' => [
                EventManager::EVENT__DELIVER => [
                    'weight' => 10,
                    'destinationState' => self::STATE__DELIVERED,
                ],
                EventManager::EVENT__REUPLOAD => [
                    'weight' => 9,
                    'destinationState' => self::STATE__RECEIVED,
                ],
            ],
            'previousStates' => [
                self::STATE__REQUESTED,
                self::STATE__TAKEN,
                self::STATE__APPROVAL_PENDING,
                self::STATE__RECEIVED,
            ],
            'originatingEvents' => [
                EventManager::EVENT__SINGLE_INSTANCE_RECEIVE,
                EventManager::EVENT__UPLOAD,
                EventManager::EVENT__APPROVE,
                EventManager::EVENT__REUPLOAD,
            ],
        ],
        self::STATE__DELIVERED => [
            'positive' => true,
            'mandatory' => true,
            'events' => [],
            'previousStates' => [
                self::STATE__RECEIVED,
            ],
            'originatingEvents' => [
                EventManager::EVENT__DELIVER,
            ],
        ],
        self::STATE__CANCELLED => [
            'positive' => false,
            'mandatory' => false,
            'events' => [],
            'previousStates' => [
                self::STATE__APPROVAL_PENDING,
                self::STATE__REQUESTED,
                self::STATE__SEARCHED,
                self::STATE__CREATED,
            ],
            'originatingEvents' => [
                EventManager::EVENT__CANCEL,
            ],
        ],
        self::STATE__ANNULLED => [
            'positive' => false,
            'mandatory' => false,
            'events' => [],
            'previousStates' => [
                self::STATE__SEARCHED,
                self::STATE__CREATED,
            ],
            'originatingEvents' => [
                EventManager::EVENT__ANNUL,
            ],
        ],
    ];

    public function __construct(
        protected EventManager $eventManager,
        protected RequestStack $requestStack
    ) { }

    public function isBefore(State $state1, State $state2): bool
    {
        return array_search(
            $state1->getType(),
            array_keys($this->graph)
        ) < array_search(
            $state2->getType(),
            array_keys($this->graph)
        );
    }

    public function createNotFoundException($message = 'Not Found', ?\Exception $previous = null): NotFoundException
    {
        return new NotFoundException($message, $previous);
    }

    public function getStateForEvent(string $event)
    {
        if (!array_key_exists($event, $this->eventManager->event_classes)) {
            throw Exception::create(Exception::NOT_FOUND, 'exception.not_found.event');
        }

        $data = null;

        foreach ($this->graph as $state) {
            if ($state['positive']) {
                foreach ($state['events'] as $key => $ev) {
                    if ($key === $event) {
                        $data = $ev['destinationState'];
                    }
                }
            }
        }

        if ($data == null && $event === EventManager::EVENT__CREATION) {
            $data = self::STATE__CREATED;
        }

        return $data;
    }

    public function getPositiveStates(): array
    {
        return array_filter(
            $this->graph,
            fn ($value) => $value['positive']
        );
    }

    public function getStateData($state)
    {
        if (!array_key_exists($state, $this->graph)) {
            throw Exception::create(Exception::NOT_FOUND, 'exception.not_found.state');
        }

        return $this->graph[$state];
    }

    public function getEventsToState($state): array
    {
        if (!array_key_exists($state, $this->graph)) {
            throw Exception::create(Exception::NOT_FOUND, 'exception.not_found.state');
        }

        $data = [];

        foreach ($this->graph[$state]['previousStates'] as $previous) {
            foreach ($this->graph[$previous]['events'] as $key => $event) {
                if ($event['destinationState'] == $state) {
                    $data[$key] = $event;
                }
            }
        }

        return $data;
    }

    public function getPreviousPositiveState($state)
    {
        if (!array_key_exists($state, $this->graph)) {
            throw Exception::create(Exception::NOT_FOUND, 'exception.not_found.state');
        }

        $data = null;

        if (count($this->graph[$state]['previousStates']) == 0) {
            $data = $state;
        } else {
            foreach ($this->graph[$state]['previousStates'] as $previous) {
                if ($this->graph[$previous]['positive']) {
                    $data = $previous;
                }
            }
        }

        return $data;
    }

    public function getPreviousMandatoryStates($state): array
    {
        if (!array_key_exists($state, $this->graph)) {
            throw Exception::create(Exception::NOT_FOUND, 'exception.not_found.state');
        }

        $data = [];

        if (count($this->graph[$state]['previousStates']) == 0) {
            $data[] = $state;
        } else {
            foreach ($this->graph[$state]['previousStates'] as $previous) {
                if ($this->graph[$previous]['mandatory']) {
                    $data[] = $previous;
                }
            }
        }

        return $data;
    }

    public function extraUndoActions(State $state): void
    {
        switch ($state->getType()) {
            case self::STATE__SEARCHED:
                $searches = $this->eventManager->getEvents(EventManager::EVENT__SEARCH, $state->getRequest()->getId());
                $this->eventManager->cancelSearches($searches);
                break;
            case self::STATE__REQUESTED:
                $httpRequest = $this->requestStack->getCurrentRequest();
                $httpRequest->request->set('observations', 'undo');
                $extraData = $this->eventManager->prepareExtraData(EventManager::EVENT__CANCEL, $state->getRequest(), $state->getInstance());
                $this->eventManager->cancelRequests(array_merge($extraData['sirequests'], $extraData['mirequests']), $extraData['httprequest']);
                $httpRequest->request->remove('observations');
                break;
            default:
        }
    }

}
