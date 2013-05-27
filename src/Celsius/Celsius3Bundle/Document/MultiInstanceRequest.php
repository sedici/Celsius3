<?php

namespace Celsius\Celsius3Bundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius\Celsius3Bundle\Helper\LifecycleHelper;
use Celsius\Celsius3Bundle\Manager\StateManager;
use Celsius\Celsius3Bundle\Document\Mixin\ReclaimableTrait;
use Celsius\Celsius3Bundle\Document\Mixin\CancellableTrait;
use Celsius\Celsius3Bundle\Document\Mixin\ProviderTrait;
use Celsius\Celsius3Bundle\Document\Mixin\AnnullableTrait;

/**
 * @MongoDB\Document
 */
class MultiInstanceRequest extends MultiInstance
{
    use AnnullableTrait, ReclaimableTrait, CancellableTrait, ProviderTrait;

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
}
