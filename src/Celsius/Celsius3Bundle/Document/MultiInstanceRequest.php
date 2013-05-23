<?php

namespace Celsius\Celsius3Bundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius\Celsius3Bundle\Helper\LifecycleHelper;
use Celsius\Celsius3Bundle\Manager\StateManager;

/**
 * @MongoDB\Document
 */
class MultiInstanceRequest extends MultiInstance
{
    /**
     * @Assert\NotNull
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
    private $isAnnulled = false;

    /**
     * @Assert\NotBlank
     * @Assert\Type(type="boolean")
     * @MongoDB\Boolean
     */
    private $isReclaimed = false;

    /**
     * @MongoDB\ReferenceOne(targetDocument="State", inversedBy="remoteEvents", cascade={"persist", "refresh"})
     */
    private $remoteState;

    public function applyExtraData(Order $order, array $data,
            LifecycleHelper $lifecycleHelper, $date)
    {
        $this->setProvider($data['extraData']['provider']);
        $this->setObservations($data['extraData']['observations']);
        $this
                ->setRemoteInstance(
                        $data['extraData']['provider']->getCelsiusInstance());
        $data['instance'] = $this->getRemoteInstance();
        $data['stateName'] = StateManager::STATE__CREATED;
        $this
                ->setRemoteState(
                        $lifecycleHelper->getState($order, $this, $data, $this));
    }

    /**
     * Set remoteState
     *
     * @param Celsius\Celsius3Bundle\Document\State $remoteState
     * @return MultiInstanceRequest
     */
    public function setRemoteState(
            \Celsius\Celsius3Bundle\Document\State $remoteState)
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
     * Set isAnnulled
     *
     * @param boolean $isAnnulled
     * @return self
     */
    public function setIsAnnulled($isAnnulled)
    {
        $this->isAnnulled = $isAnnulled;
        return $this;
    }

    /**
     * Get isAnnulled
     *
     * @return boolean $isAnnulled
     */
    public function getIsAnnulled()
    {
        return $this->isAnnulled;
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
