<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 * @MongoDB\Document(repositoryClass="Celsius\Celsius3Bundle\Repository\StateRepository")
 */
class State
{

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Date()
     * @MongoDB\Date
     */
    private $date;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="bool")
     * @MongoDB\Boolean
     */
    private $isCurrent = true;

    /**
     * @MongoDB\ReferenceOne(targetDocument="StateType", inversedBy="states")
     */
    private $type;

    /**
     * @MongoDB\ReferenceOne
     */
    private $remoteEvent;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Instance", inversedBy="states")
     */
    private $instance;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Event", mappedBy="state")
     */
    private $events;
    
    /**
     * @MongoDB\ReferenceMany(targetDocument="MultiInstanceReceive", mappedBy="remoteState")
     */
    private $remoteEvents;

    /**
     * @MongoDB\ReferenceOne(targetDocument="State")
     */
    private $previous;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Order", inversedBy="states")
     */
    private $order;

    public function __construct()
    {
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param date $date
     * @return State
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
     * Set type
     *
     * @param Celsius\Celsius3Bundle\Document\StateType $type
     * @return State
     */
    public function setType(\Celsius\Celsius3Bundle\Document\StateType $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return Celsius\Celsius3Bundle\Document\StateType $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set instance
     *
     * @param Celsius\Celsius3Bundle\Document\Instance $instance
     * @return State
     */
    public function setInstance(\Celsius\Celsius3Bundle\Document\Instance $instance)
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * Get instance
     *
     * @return Celsius\Celsius3Bundle\Document\Instance $instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Set previous
     *
     * @param Celsius\Celsius3Bundle\Document\State $previous
     * @return State
     */
    public function setPrevious(\Celsius\Celsius3Bundle\Document\State $previous = null)
    {
        $this->previous = $previous;
        return $this;
    }

    /**
     * Get previous
     *
     * @return Celsius\Celsius3Bundle\Document\State $previous
     */
    public function getPrevious()
    {
        return $this->previous;
    }

    /**
     * Set remoteEvent
     *
     * @param Celsius\Celsius3Bundle\Document\Event $remoteEvent
     * @return State
     */
    public function setRemoteEvent(\Celsius\Celsius3Bundle\Document\Event $remoteEvent)
    {
        $this->remoteEvent = $remoteEvent;
        return $this;
    }

    /**
     * Get remoteEvent
     *
     * @return Celsius\Celsius3Bundle\Document\Event $remoteEvent
     */
    public function getRemoteEvent()
    {
        return $this->remoteEvent;
    }

    /**
     * Set order
     *
     * @param Celsius\Celsius3Bundle\Document\Order $order
     * @return State
     */
    public function setOrder(\Celsius\Celsius3Bundle\Document\Order $order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Get order
     *
     * @return Celsius\Celsius3Bundle\Document\Order $order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set isCurrent
     *
     * @param boolean $isCurrent
     * @return State
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
     * Add events
     *
     * @param Celsius\Celsius3Bundle\Document\Event $events
     */
    public function addEvents(\Celsius\Celsius3Bundle\Document\Event $events)
    {
        $this->events[] = $events;
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
     * Add events
     *
     * @param Celsius\Celsius3Bundle\Document\Event $events
     */
    public function addEvent(\Celsius\Celsius3Bundle\Document\Event $events)
    {
        $this->events[] = $events;
    }

    /**
    * Remove events
    *
    * @param <variableType$events
    */
    public function removeEvent(\Celsius\Celsius3Bundle\Document\Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Add remoteEvents
     *
     * @param Celsius\Celsius3Bundle\Document\Event $remoteEvents
     */
    public function addRemoteEvent(\Celsius\Celsius3Bundle\Document\Event $remoteEvents)
    {
        $this->remoteEvents[] = $remoteEvents;
    }

    /**
    * Remove remoteEvents
    *
    * @param <variableType$remoteEvents
    */
    public function removeRemoteEvent(\Celsius\Celsius3Bundle\Document\Event $remoteEvents)
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
}
