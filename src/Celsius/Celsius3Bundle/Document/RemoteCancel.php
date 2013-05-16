<?php

namespace Celsius\Celsius3Bundle\Document;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius\Celsius3Bundle\Helper\LifecycleHelper;
use Celsius\Celsius3Bundle\Manager\StateManager;
use Celsius\Celsius3Bundle\Manager\EventManager;

/**
 * @MongoDB\Document
 */
class RemoteCancel extends MultiInstance
{
    /**
     * @MongoDB\ReferenceOne(targetDocument="State", inversedBy="remoteEvents", cascade={"persist", "refresh"})
     */
    private $remoteState;

    public function applyExtraData(Order $order, array $data,
            LifecycleHelper $lifecycleHelper, $date)
    {
        $data['extraData']['request']->setIsCancelled(true);
        $lifecycleHelper->refresh($data['extraData']['request']);
        $this
                ->setRemoteInstance(
                        $data['extraData']['request']->getRemoteInstance());
        $lifecycleHelper
                ->createEvent(EventManager::EVENT__CANCEL, $order,
                        $this->getRemoteInstance());

        //         $data['instance'] = $this->getRemoteInstance();
        //         $data['stateName'] = StateManager::STATE__CANCELLED;
        //         $this
        //                 ->setRemoteState(
        //                         $lifecycleHelper->getState($order, $this, $data, $this));
    }

    /**
     * Set remoteState
     *
     * @param Celsius\Celsius3Bundle\Document\State $remoteState
     * @return self
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
