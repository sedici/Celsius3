<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class MultiInstanceRequest extends MultiInstance
{

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Provider",
     *     discriminatorField="type",
     *     discriminatorMap={
     *         "institution"="Institution",
     *         "web"="Web"
     *     }
     * )
     */
    private $provider;

    /**
     * @MongoDB\ReferenceOne(targetDocument="State", inversedBy="remoteEvent")
     */
    private $remoteState;

    /**
     * Set remoteState
     *
     * @param Celsius\Celsius3Bundle\Document\State $remoteState
     * @return MultiInstanceRequest
     */
    public function setRemoteState(\Celsius\Celsius3Bundle\Document\State $remoteState)
    {
        $this->remoteState = $remoteState;
        return $this;
    }

    /**
     * Get remoteState
     *
     * @return Celsius\Celsius3Bundle\Document\State $remoteState
     */
    public function getRemoteState()
    {
        return $this->remoteState;
    }

    /**
     * Set provider
     *
     * @param Celsius\Celsius3Bundle\Document\Provider $provider
     * @return \MultiInstanceRequest
     */
    public function setProvider(\Celsius\Celsius3Bundle\Document\Provider $provider)
    {
        $this->provider = $provider;
        return $this;
    }

    /**
     * Get provider
     *
     * @return Celsius\Celsius3Bundle\Document\Provider $provider
     */
    public function getProvider()
    {
        return $this->provider;
    }

}
