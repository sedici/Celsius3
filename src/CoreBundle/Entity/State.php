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

namespace Celsius3\CoreBundle\Entity;

use Celsius3\CoreBundle\Entity\Event\Event;
use Celsius3\CoreBundle\Entity\Event\MultiInstanceReceiveEvent;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Celsius3\CoreBundle\Repository\StateRepository")
 * @ORM\Table(name="state", indexes={
 *   @ORM\Index(name="idx_current", columns={"current"}),
 *   @ORM\Index(name="idx_type", columns={"type"}),
 *   @ORM\Index(name="idx_previous", columns={"previous_id"}),
 *   @ORM\Index(name="idx_request", columns={"request_id"}),
 *   @ORM\Index(name="idx_instance", columns={"instance_id"}),
 *   @ORM\Index(name="idx_operator", columns={"operator_id"})
 * })
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class State
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @Assert\NotBlank
     * @Assert\Type(type="boolean")
     * @ORM\Column(type="boolean")
     */
    private $current = true;
    /**
     * @Assert\NotBlank
     * @Assert\Type(type="boolean")
     * @ORM\Column(type="boolean")
     */
    private $searchPending = false;
    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $type;
    /**
     * @ORM\OneToOne(targetEntity="Celsius3\CoreBundle\Entity\Event\Event", cascade={"persist"})
     * @ORM\JoinColumn(name="remote_event_id", referencedColumnName="id")
     */
    private $remoteEvent;
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Instance", inversedBy="states")
     * @ORM\JoinColumn(name="instance_id", referencedColumnName="id", nullable=false)
     */
    private $instance;
    /**
     * @ORM\OneToMany(targetEntity="Celsius3\CoreBundle\Entity\Event\Event", mappedBy="state", cascade={"persist"})
     */
    private $events;
    /**
     * @ORM\OneToMany(targetEntity="Celsius3\CoreBundle\Entity\Event\MultiInstanceReceiveEvent", mappedBy="remoteState")
     */
    private $remoteEvents;
    /**
     * @ORM\ManyToOne(targetEntity="State")
     * @ORM\JoinColumn(name="previous_id", referencedColumnName="id")
     */
    private $previous;
    /**
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="Request", inversedBy="states")
     * @ORM\JoinColumn(name="request_id", referencedColumnName="id", nullable=false)
     */
    private $request;
    /**
     * @ORM\ManyToOne(targetEntity="BaseUser")
     * @ORM\JoinColumn(name="operator_id", referencedColumnName="id")
     */
    private $operator;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->remoteEvents = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getType();
    }

    /**
     * Get id.
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set current.
     *
     * @param bool $current
     *
     * @return self
     */
    public function setCurrent($current)
    {
        $this->current = $current;

        return $this;
    }

    /**
     * Get current.
     *
     * @return bool $current
     */
    public function isCurrent()
    {
        return $this->current;
    }

    /**
     * Set searchPending.
     *
     * @param bool $searchPending
     *
     * @return self
     */
    public function setSearchPending($searchPending)
    {
        $this->searchPending = $searchPending;

        return $this;
    }

    /**
     * Get searchPending.
     *
     * @return bool $searchPending
     */
    public function getSearchPending()
    {
        return $this->searchPending;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set remoteEvent.
     *
     * @param $remoteEvent
     *
     * @return self
     */
    public function setRemoteEvent($remoteEvent)
    {
        $this->remoteEvent = $remoteEvent;

        return $this;
    }

    /**
     * Get remoteEvent.
     *
     * @return $remoteEvent
     */
    public function getRemoteEvent()
    {
        return $this->remoteEvent;
    }

    /**
     * Set instance.
     *
     * @param Instance $instance
     *
     * @return self
     */
    public function setInstance(Instance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance.
     *
     * @return Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Add events.
     *
     * @param Event $events
     */
    public function addEvent(Event $events)
    {
        $this->events[] = $events;
    }

    /**
     * Remove events.
     *
     * @param Event $events
     */
    public function removeEvent(Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events.
     *
     * @return Collection $events
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Add remoteEvents.
     *
     * @param MultiInstanceReceiveEvent $remoteEvents
     */
    public function addRemoteEvent(MultiInstanceReceiveEvent $remoteEvents)
    {
        $this->remoteEvents[] = $remoteEvents;
    }

    /**
     * Remove remoteEvents.
     *
     * @param MultiInstanceReceiveEvent $remoteEvents
     */
    public function removeRemoteEvent(MultiInstanceReceiveEvent $remoteEvents)
    {
        $this->remoteEvents->removeElement($remoteEvents);
    }

    /**
     * Get remoteEvents.
     *
     * @return Collection $remoteEvents
     */
    public function getRemoteEvents()
    {
        return $this->remoteEvents;
    }

    /**
     * Set previous.
     *
     * @param State $previous
     *
     * @return self
     */
    public function setPrevious(State $previous = null)
    {
        $this->previous = $previous;

        return $this;
    }

    /**
     * Get previous.
     *
     * @return State $previous
     */
    public function getPrevious()
    {
        return $this->previous;
    }

    /**
     * Set request.
     *
     * @param Request $request
     *
     * @return self
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get request.
     *
     * @return Request $request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set operator.
     *
     * @param BaseUser $operator
     *
     * @return self
     */
    public function setOperator(BaseUser $operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator.
     *
     * @return BaseUser $operator
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Get current.
     *
     * @return bool
     */
    public function getCurrent()
    {
        return $this->current;
    }
}
