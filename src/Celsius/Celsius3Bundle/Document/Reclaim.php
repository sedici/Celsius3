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
     * @MongoDB\ReferenceOne(targetDocument="Event")
     */
    private $requestEvent;

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
     * Set requestEvent
     *
     * @param Celsius\Celsius3Bundle\Document\Event $requestEvent
     * @return \Reclaim
     */
    public function setRequestEvent(\Celsius\Celsius3Bundle\Document\Event $requestEvent)
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
