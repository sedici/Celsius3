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

namespace Celsius3\Entity\Event;

use Celsius3\Entity\Request;
use Celsius3\Helper\LifecycleHelper;
use Celsius3\Manager\StateManager;
use Celsius3\NotificationBundle\Entity\Notifiable;
use Celsius3\NotificationBundle\Manager\NotificationManager;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use function array_key_exists;

/**
 * @ORM\Entity(repositoryClass="Celsius3\Repository\ReclaimEventRepository")
 */
class ReclaimEvent extends SingleInstanceEvent implements Notifiable
{
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Celsius3\Entity\Event\Event")
     * @ORM\JoinColumn(name="request_event_id", referencedColumnName="id")
     */
    private $requestEvent;
    /**
     * @ORM\ManyToOne(targetEntity="Celsius3\Entity\Event\Event")
     * @ORM\JoinColumn(name="receive_event_id", referencedColumnName="id")
     */
    private $receiveEvent;

    public function getEventType(): string
    {
        return 'reclaim';
    }

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date): void
    {
        if (array_key_exists('request', $data['extraData'])) {
            $this->setRequestEvent($data['extraData']['request']);
            $this->getRequestEvent()->setReclaimed(true);
        } else {
            $this->setReceiveEvent($data['extraData']['receive']);
            $this->getReceiveEvent()->setReclaimed(true);
            if ($data['extraData']['receive'] instanceof UploadEvent) {
                // Se vuelve a posicionar el currentState en requested
                $r = $this->getReceiveEvent()->getRequest();
                $old = $r->getCurrentState()->setCurrent(false);
                $new = $r->getState(StateManager::STATE__TAKEN)->setCurrent(true);
                $lifecycleHelper->refresh($old);
                $lifecycleHelper->refresh($new);
            } else {
                $this->setRequestEvent($this->getReceiveEvent()->getRequestEvent());
                $this->getRequestEvent()->setReclaimed(true);

                // Se vuelve a posicionar el currentState en taken
                $r = $this->getRequestEvent()->getRequest();
                $old = $r->getCurrentState()->setCurrent(false);
                $new = $r->getState(StateManager::STATE__REQUESTED)->setCurrent(true);
                $lifecycleHelper->refresh($old);
                $lifecycleHelper->refresh($new);
            }
        }
        $this->setObservations($data['extraData']['observations']);
    }

    public function getRequestEvent(): Event
    {
        return $this->requestEvent;
    }

    public function setRequestEvent(Event $requestEvent): self
    {
        $this->requestEvent = $requestEvent;

        return $this;
    }

    public function getReceiveEvent(): Event
    {
        return $this->receiveEvent;
    }

    public function setReceiveEvent(Event $receiveEvent): self
    {
        $this->receiveEvent = $receiveEvent;

        return $this;
    }

    public function notify(NotificationManager $manager): void
    {
        if (($this->getReceiveEvent() !== null && $this->getReceiveEvent() instanceof MultiInstanceEvent) ||
            ($this->getRequestEvent() !== null && $this->getRequestEvent() instanceof MultiInstanceEvent)) {
            $manager->notifyRemoteEvent($this, 'reclaim');
        }
    }

    public function getRemoteNotificationTarget()
    {
        $operator = null;

        if ($this->getReceiveEvent() instanceof MultiInstanceEvent) {
            $operator = $this->getRequest()->getOrder()->getRequest(
                $this->getReceiveEvent()->getInstance()
            )->getOperator();
        }

        if ($this->getRequestEvent() instanceof MultiInstanceEvent) {
            $operator = $this->getRequest()->getOrder()->getRequest(
                $this->getRequestEvent()->getRemoteRequest()->getInstance()
            )->getOperator();
        }

        return $operator;
    }
}
