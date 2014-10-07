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

namespace Celsius3\CoreBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ODM\Document(repositoryClass="Celsius3\CoreBundle\Repository\StateRepository")
 * @ODM\Indexes({
 *   @ODM\Index(keys={"instance.id"="asc", "request.id"="asc", "owner.id"="asc"}),
 *   @ODM\Index(keys={"instance.id"="asc", "request.id"="asc", "operator.id"="asc"}),
 *   @ODM\Index(keys={"instance.id"="asc", "isCurrent"="asc", "type"="asc", "requestType"="asc", "owner.id"="asc", "operator.id"="asc", "librarian.id"="asc"}),
 * })
 */
class State
{
    use TimestampableDocument;
    /**
     * @ODM\Id
     */
    private $id;
    /**
     * @Assert\NotBlank
     * @Assert\Date
     * @ODM\Date
     */
    private $date;
    /**
     * @Assert\NotBlank
     * @Assert\Type(type="bool")
     * @ODM\Boolean
     */
    private $isCurrent = true;
    /**
     * @Assert\NotNull
     * @ODM\String
     */
    private $type;
    /**
     * @Assert\NotBlank
     * @Assert\Choice(callback = {"\Celsius3\CoreBundle\Manager\OrderManager", "getTypes"}, message = "Choose a valid type.")
     * @ODM\String
     */
    private $requestType;
    /**
     * @ODM\ReferenceOne
     */
    private $remoteEvent;
    /**
     * @Assert\NotNull
     * @ODM\ReferenceOne(targetDocument="Instance", inversedBy="states")
     */
    private $instance;
    /**
     * @ODM\ReferenceMany(targetDocument="Celsius3\CoreBundle\Document\Event\Event", mappedBy="state")
     */
    private $events;
    /**
     * @ODM\ReferenceMany(targetDocument="Celsius3\CoreBundle\Document\Event\MultiInstanceReceiveEvent", mappedBy="remoteState")
     */
    private $remoteEvents;
    /**
     * @ODM\ReferenceOne(targetDocument="State")
     */
    private $previous;
    /**
     * @Assert\NotNull
     * @ODM\ReferenceOne(targetDocument="Request", inversedBy="states")
     */
    private $request;
    /**
     * @Assert\NotNull
     * @ODM\ReferenceOne(targetDocument="BaseUser")
     */
    private $owner;
    /**
     * @ODM\ReferenceOne(targetDocument="BaseUser")
     */
    private $librarian;
    /**
     * @ODM\ReferenceOne(targetDocument="BaseUser")
     */
    private $operator;
    /**
     * @ODM\ReferenceOne(targetDocument="Order")
     */
    private $order;

    public function __construct()
    {
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->getType();
    }

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param  date $date
     * @return self
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return date $date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set isCurrent
     *
     * @param  boolean $isCurrent
     * @return self
     */
    public function setIsCurrent($isCurrent)
    {
        $this->isCurrent = $isCurrent;

        return $this;
    }

    /**
     * Get isCurrent
     *
     * @return boolean $isCurrent
     */
    public function getIsCurrent()
    {
        return $this->isCurrent;
    }

    /**
     * Set type
     *
     * @param  string $type
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set remoteEvent
     *
     * @param $remoteEvent
     * @return self
     */
    public function setRemoteEvent($remoteEvent)
    {
        $this->remoteEvent = $remoteEvent;

        return $this;
    }

    /**
     * Get remoteEvent
     *
     * @return $remoteEvent
     */
    public function getRemoteEvent()
    {
        return $this->remoteEvent;
    }

    /**
     * Set instance
     *
     * @param  Celsius3\CoreBundle\Document\Instance $instance
     * @return self
     */
    public function setInstance(\Celsius3\CoreBundle\Document\Instance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance
     *
     * @return Celsius3\CoreBundle\Document\Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Add events
     *
     * @param Celsius3\CoreBundle\Document\Event\Event $events
     */
    public function addEvent(\Celsius3\CoreBundle\Document\Event\Event $events)
    {
        $this->events[] = $events;
    }

    /**
     * Remove events
     *
     * @param Celsius3\CoreBundle\Document\Event\Event $events
     */
    public function removeEvent(\Celsius3\CoreBundle\Document\Event\Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return Doctrine\Common\Collections\Collection $events
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Add remoteEvents
     *
     * @param Celsius3\CoreBundle\Document\Event\MultiInstanceReceiveEvent $remoteEvents
     */
    public function addRemoteEvent(\Celsius3\CoreBundle\Document\Event\MultiInstanceReceiveEvent $remoteEvents)
    {
        $this->remoteEvents[] = $remoteEvents;
    }

    /**
     * Remove remoteEvents
     *
     * @param Celsius3\CoreBundle\Document\Event\MultiInstanceReceiveEvent $remoteEvents
     */
    public function removeRemoteEvent(\Celsius3\CoreBundle\Document\Event\MultiInstanceReceiveEvent $remoteEvents)
    {
        $this->remoteEvents->removeElement($remoteEvents);
    }

    /**
     * Get remoteEvents
     *
     * @return Doctrine\Common\Collections\Collection $remoteEvents
     */
    public function getRemoteEvents()
    {
        return $this->remoteEvents;
    }

    /**
     * Set previous
     *
     * @param  Celsius3\CoreBundle\Document\State $previous
     * @return self
     */
    public function setPrevious(\Celsius3\CoreBundle\Document\State $previous = null)
    {
        $this->previous = $previous;

        return $this;
    }

    /**
     * Get previous
     *
     * @return Celsius3\CoreBundle\Document\State $previous
     */
    public function getPrevious()
    {
        return $this->previous;
    }

    /**
     * Set request
     *
     * @param  Celsius3\CoreBundle\Document\Request $request
     * @return self
     */
    public function setRequest(\Celsius3\CoreBundle\Document\Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get request
     *
     * @return Celsius3\CoreBundle\Document\Request $request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set owner
     *
     * @param Celsius3\CoreBundle\Document\BaseUser $owner
     * @return self
     */
    public function setOwner(\Celsius3\CoreBundle\Document\BaseUser $owner)
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * Get owner
     *
     * @return Celsius3\CoreBundle\Document\BaseUser $owner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set librarian
     *
     * @param Celsius3\CoreBundle\Document\BaseUser $librarian
     * @return self
     */
    public function setLibrarian(\Celsius3\CoreBundle\Document\BaseUser $librarian)
    {
        $this->librarian = $librarian;
        return $this;
    }

    /**
     * Get librarian
     *
     * @return Celsius3\CoreBundle\Document\BaseUser $librarian
     */
    public function getLibrarian()
    {
        return $this->librarian;
    }

    /**
     * Set operator
     *
     * @param Celsius3\CoreBundle\Document\BaseUser $operator
     * @return self
     */
    public function setOperator(\Celsius3\CoreBundle\Document\BaseUser $operator)
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * Get operator
     *
     * @return Celsius3\CoreBundle\Document\BaseUser $operator
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set order
     *
     * @param Celsius3\CoreBundle\Document\Order $order
     * @return self
     */
    public function setOrder(\Celsius3\CoreBundle\Document\Order $order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Get order
     *
     * @return Celsius3\CoreBundle\Document\Order $order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set requestType
     *
     * @param string $requestType
     * @return self
     */
    public function setRequestType($requestType)
    {
        $this->requestType = $requestType;
        return $this;
    }

    /**
     * Get requestType
     *
     * @return string $requestType
     */
    public function getRequestType()
    {
        return $this->requestType;
    }
}
