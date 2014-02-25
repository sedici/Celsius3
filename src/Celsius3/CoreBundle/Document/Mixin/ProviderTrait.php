<?php

namespace Celsius3\CoreBundle\Document\Mixin;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

trait ProviderTrait
{

    /**
     * @Assert\NotNull(groups={"request"})
     * @MongoDB\ReferenceOne
     */
    private $provider;

    /**
     * Set provider
     *
     * @param Celsius3\CoreBundle\Document\Provider $provider
     * @return \SingleInstanceRequest
     */
    public function setProvider(\Celsius3\CoreBundle\Document\Provider $provider)
    {
        $this->provider = $provider;
        return $this;
    }

    /**
     * Get provider
     *
     * @return Celsius3\CoreBundle\Document\Provider $provider
     */
    public function getProvider()
    {
        return $this->provider;
    }

}
