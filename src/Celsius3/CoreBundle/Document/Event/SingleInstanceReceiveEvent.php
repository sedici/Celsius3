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
use Celsius3\CoreBundle\Document\Mixin\ReclaimableTrait;
use Celsius3\CoreBundle\Document\Request;

/**
 * @ODM\Document
 */
class SingleInstanceReceiveEvent extends SingleInstanceEvent
{
    use ReclaimableTrait;
    /**
     * @Assert\NotBlank
     * @ODM\String
     */
    private $deliveryType;
    /**
     * @ODM\ReferenceMany(targetDocument="Celsius3\CoreBundle\Document\File", mappedBy="event", cascade={"persist"})
     */
    private $files;
    /**
     * @Assert\NotNull
     * @ODM\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\Event\Event")
     */
    private $requestEvent;

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {
        $this->setRequestEvent($data['extraData']['request']);
        $this->setObservations($data['extraData']['observations']);
        $lifecycleHelper->uploadFiles($request, $this, $data['extraData']['files']);
    }

    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set deliveryType
     *
     * @param  string $deliveryType
     * @return self
     */
    public function setDeliveryType($deliveryType)
    {
        $this->deliveryType = $deliveryType;

        return $this;
    }

    /**
     * Get deliveryType
     *
     * @return string $deliveryType
     */
    public function getDeliveryType()
    {
        return $this->deliveryType;
    }

    /**
     * Add files
     *
     * @param Celsius3\CoreBundle\Document\File $files
     */
    public function addFile(\Celsius3\CoreBundle\Document\File $files)
    {
        $this->files[] = $files;
    }

    /**
     * Remove files
     *
     * @param Celsius3\CoreBundle\Document\File $files
     */
    public function removeFile(\Celsius3\CoreBundle\Document\File $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return Doctrine\Common\Collections\Collection $files
     */
    public function getFiles()
    {
        return $this->files;
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
}