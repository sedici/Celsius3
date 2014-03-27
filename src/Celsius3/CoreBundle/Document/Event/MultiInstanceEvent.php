<?php

namespace Celsius3\CoreBundle\Document\Event;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class MultiInstanceEvent extends Event
{

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\Instance", inversedBy="events")
     */
    private $remoteInstance;

    /**
     * Set remoteInstance
     *
     * @param Celsius3\CoreBundle\Document\Instance $remoteInstance
     * @return self
     */
    public function setRemoteInstance(\Celsius3\CoreBundle\Document\Instance $remoteInstance)
    {
        $this->remoteInstance = $remoteInstance;
        return $this;
    }

    /**
     * Get remoteInstance
     *
     * @return Celsius3\CoreBundle\Document\Instance $remoteInstance
     */
    public function getRemoteInstance()
    {
        return $this->remoteInstance;
    }

}
