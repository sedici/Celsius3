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

namespace Celsius3\CoreBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Celsius3\CoreBundle\Repository\StateRepository")
 * @ORM\Table(name="state", indexes={
 *   @ORM\Index(name="idx_current", columns={"isCurrent"}),
 *   @ORM\Index(name="idx_type", columns={"type"}),
 *   @ORM\Index(name="idx_previous", columns={"previous_id"}),
 *   @ORM\Index(name="idx_request", columns={"request_id"}),
 *   @ORM\Index(name="idx_instance", columns={"instance_id"}),
 *   @ORM\Index(name="idx_operator", columns={"operator_id"})
 * })
 */
class State
{

    use TimestampableEntity;
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
    private $isCurrent = true;
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
     * @ORM\OneToOne(targetEntity="Celsius3\CoreBundle\Entity\Event\Event")
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
     * @ORM\OneToMany(targetEntity="Celsius3\CoreBundle\Entity\Event\Event", mappedBy="state")
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
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
        $this->remoteEvents = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set searchPending
     *
     * @param  boolean $searchPending
     * @return self
     */
    public function setSearchPending($searchPending)
    {
        $this->searchPending = $searchPending;

        return $this;
    }

    /**
     * Get searchPending
     *
     * @return boolean $searchPending
     */
    public function getSearchPending()
    {
        return $this->searchPending;
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
     * @param  Celsius3\CoreBundle\Entity\Instance $instance
     * @return self
     */
    public function setInstance(\Celsius3\CoreBundle\Entity\Instance $instance)
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * Get instance
     *
     * @return Celsius3\CoreBundle\Entity\Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Add events
     *
     * @param Celsius3\CoreBundle\Entity\Event\Event $events
     */
    public function addEvent(\Celsius3\CoreBundle\Entity\Event\Event $events)
    {
        $this->events[] = $events;
    }

    /**
     * Remove events
     *
     * @param Celsius3\CoreBundle\Entity\Event\Event $events
     */
    public function removeEvent(\Celsius3\CoreBundle\Entity\Event\Event $events)
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
     * @param Celsius3\CoreBundle\Entity\Event\MultiInstanceReceiveEvent $remoteEvents
     */
    public function addRemoteEvent(\Celsius3\CoreBundle\Entity\Event\MultiInstanceReceiveEvent $remoteEvents)
    {
        $this->remoteEvents[] = $remoteEvents;
    }

    /**
     * Remove remoteEvents
     *
     * @param Celsius3\CoreBundle\Entity\Event\MultiInstanceReceiveEvent $remoteEvents
     */
    public function removeRemoteEvent(\Celsius3\CoreBundle\Entity\Event\MultiInstanceReceiveEvent $remoteEvents)
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
     * @param  Celsius3\CoreBundle\Entity\State $previous
     * @return self
     */
    public function setPrevious(\Celsius3\CoreBundle\Entity\State $previous = null)
    {
        $this->previous = $previous;

        return $this;
    }

    /**
     * Get previous
     *
     * @return Celsius3\CoreBundle\Entity\State $previous
     */
    public function getPrevious()
    {
        return $this->previous;
    }

    /**
     * Set request
     *
     * @param  Celsius3\CoreBundle\Entity\Request $request
     * @return self
     */
    public function setRequest(\Celsius3\CoreBundle\Entity\Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get request
     *
     * @return Celsius3\CoreBundle\Entity\Request $request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set operator
     *
     * @param Celsius3\CoreBundle\Entity\BaseUser $operator
     * @return self
     */
    public function setOperator(\Celsius3\CoreBundle\Entity\BaseUser $operator)
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * Get operator
     *
     * @return Celsius3\CoreBundle\Entity\BaseUser $operator
     */
    public function getOperator()
    {
        return $this->operator;
    }
}
