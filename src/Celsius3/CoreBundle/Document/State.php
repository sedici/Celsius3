<?php

namespace Celsius3\CoreBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 * @MongoDB\Document(repositoryClass="Celsius3\CoreBundle\Repository\StateRepository")
 */
class State
{

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @Assert\Date
     * @MongoDB\Date
     */
    private $date;

    /**
     * @Assert\NotBlank
     * @Assert\Type(type="bool")
     * @MongoDB\Boolean
     */
    private $isCurrent = true;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="StateType", inversedBy="states")
     */
    private $type;

    /**
     * @MongoDB\ReferenceOne
     */
    private $remoteEvent;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Instance", inversedBy="states")
     */
    private $instance;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Celsius3\CoreBundle\Document\Event\Event", mappedBy="state")
     */
    private $events;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Celsius3\CoreBundle\Document\Event\MultiInstanceReceiveEvent", mappedBy="remoteState")
     */
    private $remoteEvents;

    /**
     * @MongoDB\ReferenceOne(targetDocument="State")
     */
    private $previous;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Request", inversedBy="states")
     */
    private $request;

    public function __construct()
    {
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->getType()->__toString();
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
     * @param  Celsius3\CoreBundle\Document\StateType $type
     * @return self
     */
    public function setType(\Celsius3\CoreBundle\Document\StateType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return Celsius3\CoreBundle\Document\StateType $type
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

}
