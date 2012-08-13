<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
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
     * @MongoDB\ReferenceOne(targetDocument="StateType", inversedBy="states")
     */
    private $type;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Event", mappedBy="state")
     */
    private $events;
    
    /**
     * @MongoDB\ReferenceOne(targetDocument="Event", mappedBy="remoteState")
     */
    private $remoteEvent;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Instance", inversedBy="states")
     */
    private $instance;

    /**
     * @MongoDB\ReferenceOne(targetDocument="State")
     */
    private $previous;

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
    public function setPrevious(\Celsius\Celsius3Bundle\Document\State $previous)
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
}
