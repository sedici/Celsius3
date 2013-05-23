<?php

namespace Celsius\Celsius3Bundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Reclaim extends SingleInstance
{
    /**
     * @Assert\NotBlank()
     * @MongoDB\String
     */
    private $observations;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Event")
     */
    private $requestEvent;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Event")
     */
    private $receiveEvent;

    /**
     * Set observations
     *
     * @param string $observations
     * @return \Reclaim
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;
        return $this;
    }

    /**
     * Get observations
     *
     * @return string $observations
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * Set receiveEvent
     *
     * @param Celsius\Celsius3Bundle\Document\Event $receiveEvent
     * @return self
     */
    public function setReceiveEvent(
            \Celsius\Celsius3Bundle\Document\Event $receiveEvent)
    {
        $this->receiveEvent = $receiveEvent;
        return $this;
    }

    /**
     * Get receiveEvent
     *
     * @return Celsius\Celsius3Bundle\Document\Event $receiveEvent
     */
    public function getReceiveEvent()
    {
        return $this->receiveEvent;
    }

    /**
     * Set requestEvent
     *
     * @param Celsius\Celsius3Bundle\Document\Event $requestEvent
     * @return self
     */
    public function setRequestEvent(
            \Celsius\Celsius3Bundle\Document\Event $requestEvent)
    {
        $this->requestEvent = $requestEvent;
        return $this;
    }

    /**
     * Get requestEvent
     *
     * @return Celsius\Celsius3Bundle\Document\Event $requestEvent
     */
    public function getRequestEvent()
    {
        return $this->requestEvent;
    }
}
