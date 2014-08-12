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

namespace Celsius3\CoreBundle\Document\Event;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Document\Request;

/**
 * @ODM\Document
 */
class ReclaimEvent extends SingleInstanceEvent
{
    /**
     * @Assert\NotNull
     * @ODM\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\Event\Event")
     */
    private $requestEvent;
    /**
     * @ODM\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\Event\Event")
     */
    private $receiveEvent;

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {
        if (array_key_exists('request', $data['extraData'])) {
            $this->setRequestEvent($data['extraData']['request']);
        } else {
            $this->setReceiveEvent($data['extraData']['receive']);
            if (!($data['extraData']['receive'] instanceof UploadEvent)) {
                $this->setRequestEvent($this->getReceiveEvent()->getRequestEvent());
            }
        }
        $this->setObservations($data['extraData']['observations']);
    }

    /**
     * Set requestEvent
     *
     * @param  Celsius3\CoreBundle\Document\Event\Event $requestEvent
     * @return self
     */
    public function setRequestEvent(\Celsius3\CoreBundle\Document\Event\Event $requestEvent)
    {
        $this->requestEvent = $requestEvent;

        return $this;
    }

    /**
     * Get requestEvent
     *
     * @return Celsius3\CoreBundle\Document\Event\Event $requestEvent
     */
    public function getRequestEvent()
    {
        return $this->requestEvent;
    }

    /**
     * Set receiveEvent
     *
     * @param  Celsius3\CoreBundle\Document\Event\Event $receiveEvent
     * @return self
     */
    public function setReceiveEvent(\Celsius3\CoreBundle\Document\Event\Event $receiveEvent)
    {
        $this->receiveEvent = $receiveEvent;

        return $this;
    }

    /**
     * Get receiveEvent
     *
     * @return Celsius3\CoreBundle\Document\Event\Event $receiveEvent
     */
    public function getReceiveEvent()
    {
        return $this->receiveEvent;
    }
}