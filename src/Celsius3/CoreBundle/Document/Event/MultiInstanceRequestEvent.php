<?php

namespace Celsius3\CoreBundle\Document\Event;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Manager\StateManager;
use Celsius3\CoreBundle\Document\Mixin\ReclaimableTrait;
use Celsius3\CoreBundle\Document\Mixin\CancellableTrait;
use Celsius3\CoreBundle\Document\Mixin\ProviderTrait;
use Celsius3\CoreBundle\Document\Mixin\AnnullableTrait;
use Celsius3\CoreBundle\Document\Request;

/**
 * @MongoDB\Document
 */
class MultiInstanceRequestEvent extends MultiInstanceEvent
{

    use AnnullableTrait,
        ReclaimableTrait,
        CancellableTrait,
        ProviderTrait;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\State", inversedBy="remoteEvents", cascade={"persist", "refresh"})
     */
    private $remoteState;

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {
        $this->setProvider($data['extraData']['provider']);
        $this->setObservations($data['extraData']['observations']);
        $this->setRemoteInstance($data['extraData']['provider']->getCelsiusInstance());
        $data['instance'] = $this->getRemoteInstance();
        $data['stateName'] = StateManager::STATE__CREATED;
        $this->setRemoteState($lifecycleHelper->getState($request, $this, $data, $this));
    }

    /**
     * Set remoteState
     *
     * @param Celsius3\CoreBundle\Document\State $remoteState
     * @return self
     */
    public function setRemoteState(\Celsius3\CoreBundle\Document\State $remoteState)
    {
        $this->remoteState = $remoteState;
        return $this;
    }

    /**
     * Get remoteState
     *
     * @return Celsius3\CoreBundle\Document\State $remoteState
     */
    public function getRemoteState()
    {
        return $this->remoteState;
    }

}
