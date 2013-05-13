<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class MultiInstance extends Event
{

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Instance", inversedBy="events")
     */
    private $remoteInstance;

    public function notifyRemoteInstance()
    {
        return null;
    }

    /**
     * Set remoteInstance
     *
     * @param Celsius\Celsius3Bundle\Document\Instance $remoteInstance
     * @return \MultiInstance
     */
    public function setRemoteInstance(\Celsius\Celsius3Bundle\Document\Instance $remoteInstance)
    {
        $this->remoteInstance = $remoteInstance;
        return $this;
    }

    /**
     * Get remoteInstance
     *
     * @return Celsius\Celsius3Bundle\Document\Instance $remoteInstance
     */
    public function getRemoteInstance()
    {
        return $this->remoteInstance;
    }

}
