<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Approve extends MultiInstance
{

    /**
     * @MongoDB\ReferenceOne(targetDocument="Event")
     */
    private $requestEvent;

    /**
     * Set requestEvent
     *
     * @param Celsius\Celsius3Bundle\Document\Event $requestEvent
     * @return \Approve
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
