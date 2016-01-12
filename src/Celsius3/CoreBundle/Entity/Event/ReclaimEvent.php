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

namespace Celsius3\CoreBundle\Entity\Event;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Entity\Request;
use Celsius3\CoreBundle\Manager\StateManager;
use Celsius3\NotificationBundle\Entity\Notifiable;
use Celsius3\NotificationBundle\Manager\NotificationManager;

/**
 * @ORM\Entity
 */
class ReclaimEvent extends SingleInstanceEvent implements Notifiable
{
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\Event\Event")
     * @ORM\JoinColumn(name="request_event_id", referencedColumnName="id")
     */
    private $requestEvent;
    /**
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\Event\Event")
     * @ORM\JoinColumn(name="receive_event_id", referencedColumnName="id")
     */
    private $receiveEvent;

    public function getEventType()
    {
        return 'reclaim';
    }

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {
        if (array_key_exists('request', $data['extraData'])) {
            $this->setRequestEvent($data['extraData']['request']);
            $this->getRequestEvent()->setIsReclaimed(true);
        } else {
            $this->setReceiveEvent($data['extraData']['receive']);
            $this->getReceiveEvent()->setIsReclaimed(true);
            if (!($data['extraData']['receive'] instanceof UploadEvent)) {
                $this->setRequestEvent($this->getReceiveEvent()->getRequestEvent());
                $this->getRequestEvent()->setIsReclaimed(true);

                // Se vuelve a posicionar el currentState en taken
                $r = $this->getRequestEvent()->getRequest();
                $old = $r->getCurrentState()->setIsCurrent(false);
                $new = $r->getState(StateManager::STATE__REQUESTED)->setIsCurrent(true);
                $lifecycleHelper->refresh($old);
                $lifecycleHelper->refresh($new);
            } else {
                // Se vuelve a posicionar el currentState en requested
                $r = $this->getReceiveEvent()->getRequest();
                $old = $r->getCurrentState()->setIsCurrent(false);
                $new = $r->getState(StateManager::STATE__TAKEN)->setIsCurrent(true);
                $lifecycleHelper->refresh($old);
                $lifecycleHelper->refresh($new);
            }
        }
        $this->setObservations($data['extraData']['observations']);
    }

    /**
     * Set requestEvent
     *
     * @param  Celsius3\CoreBundle\Entity\Event\Event $requestEvent
     * @return self
     */
    public function setRequestEvent(\Celsius3\CoreBundle\Entity\Event\Event $requestEvent)
    {
        $this->requestEvent = $requestEvent;

        return $this;
    }

    /**
     * Get requestEvent
     *
     * @return Celsius3\CoreBundle\Entity\Event\Event $requestEvent
     */
    public function getRequestEvent()
    {
        return $this->requestEvent;
    }

    /**
     * Set receiveEvent
     *
     * @param  Celsius3\CoreBundle\Entity\Event\Event $receiveEvent
     * @return self
     */
    public function setReceiveEvent(\Celsius3\CoreBundle\Entity\Event\Event $receiveEvent)
    {
        $this->receiveEvent = $receiveEvent;

        return $this;
    }

    /**
     * Get receiveEvent
     *
     * @return Celsius3\CoreBundle\Entity\Event\Event $receiveEvent
     */
    public function getReceiveEvent()
    {
        return $this->receiveEvent;
    }

    public function notify(NotificationManager $manager)
    {
        if ((!is_null($this->getReceiveEvent()) && $this->getReceiveEvent() instanceof MultiInstanceEvent) ||
         (!is_null($this->getRequestEvent()) && $this->getRequestEvent() instanceof MultiInstanceEvent)) {
            $manager->notifyRemoteEvent($this, 'reclaim');
        }
    }

    public function getRemoteNotificationTarget()
    {
        if (!is_null($this->getReceiveEvent()) && $this->getReceiveEvent() instanceof MultiInstanceEvent){
            return $this->getRequest()->getOrder()->getRequest($this->getReceiveEvent()->getInstance())->getOperator();
        } else if (!is_null($this->getRequestEvent()) && $this->getRequestEvent() instanceof MultiInstanceEvent) {
            return $this->getRequest()->getOrder()->getRequest($this->getRequestEvent()->getRemoteRequest()->getInstance())->getOperator();
        }
    }
}
