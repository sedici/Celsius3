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
     * @MongoDB\ReferenceOne
     */
    private $provider;

    /**
     * @MongoDB\ReferenceOne(targetDocument="State", inversedBy="remoteEvents")
     */
    private $remoteState;

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

    public function applyExtraData(Order $order, array $extraData,
            LifecycleHelper $lifecycleHelper, $date)
    {
        $this->setProvider($extraData['provider']);
        $this->setObservations($extraData['observations']);
        $this->setRemoteInstance($extraData['provider']->getCelsiusInstance());
        $this
                ->setRemoteState(
                        $lifecycleHelper
                                ->getState(StateManager::STATE__CREATED, $date,
                                        $order, $this,
                                        $extraData['provider']
                                                ->getCelsiusInstance()));
    }
}
