<?php

namespace Celsius\Celsius3Bundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class SingleInstanceRequest extends SingleInstance
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
     * Set provider
     *
     * @param Celsius\Celsius3Bundle\Document\Provider $provider
     * @return \SingleInstanceRequest
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
