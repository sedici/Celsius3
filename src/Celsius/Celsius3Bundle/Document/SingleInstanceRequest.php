<?php

namespace Celsius\Celsius3Bundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius\Celsius3Bundle\Helper\LifecycleHelper;

/**
 * @MongoDB\Document
 */
class SingleInstanceRequest extends SingleInstance
{
    /**
     * @Assert\NotNull(groups={"request"})
     * @MongoDB\ReferenceOne
     */
    private $provider;

    /**
     * @Assert\NotBlank
     * @Assert\Type(type="boolean")
     * @MongoDB\Boolean
     */
    private $isCancelled = false;

    /**
     * @Assert\NotBlank
     * @Assert\Type(type="boolean")
     * @MongoDB\Boolean
     */
    private $isReclaimed = false;

    public function applyExtraData(Order $order, array $data,
            LifecycleHelper $lifecycleHelper, $date)
    {
        $this->setProvider($data['extraData']['provider']);
        $this->setObservations($data['extraData']['observations']);
    }

    /**
     * Set provider
     *
     * @param Celsius\Celsius3Bundle\Document\Provider $provider
     * @return \SingleInstanceRequest
     */
    public function setProvider(
            \Celsius\Celsius3Bundle\Document\Provider $provider)
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

    /**
     * Set isCancelled
     *
     * @param boolean $isCancelled
     * @return self
     */
    public function setIsCancelled($isCancelled)
    {
        $this->isCancelled = $isCancelled;
        return $this;
    }

    /**
     * Get isCancelled
     *
     * @return boolean $isCancelled
     */
    public function getIsCancelled()
    {
        return $this->isCancelled;
    }

    /**
     * Set isReclaimed
     *
     * @param boolean $isReclaimed
     * @return self
     */
    public function setIsReclaimed($isReclaimed)
    {
        $this->isReclaimed = $isReclaimed;
        return $this;
    }

    /**
     * Get isReclaimed
     *
     * @return boolean $isReclaimed
     */
    public function getIsReclaimed()
    {
        return $this->isReclaimed;
    }
}
