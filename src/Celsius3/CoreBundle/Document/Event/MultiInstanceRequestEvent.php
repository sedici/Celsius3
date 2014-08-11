<?php

namespace Celsius3\CoreBundle\Document\Event;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Manager\StateManager;
use Celsius3\CoreBundle\Document\Mixin\ReclaimableTrait;
use Celsius3\CoreBundle\Document\Mixin\CancellableTrait;
use Celsius3\CoreBundle\Document\Mixin\ProviderTrait;
use Celsius3\CoreBundle\Document\Mixin\AnnullableTrait;
use Celsius3\CoreBundle\Document\Request;
use Celsius3\CoreBundle\Manager\OrderManager;

/**
 * @ODM\Document
 */
class MultiInstanceRequestEvent extends MultiInstanceEvent
{
    use ReclaimableTrait,
        CancellableTrait,
        AnnullableTrait,
        ProviderTrait;
    /**
     * @ODM\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\Request", inversedBy="remoteEvents", cascade={"persist", "refresh"})
     */
    private $remoteRequest;

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {
        $this->setProvider($data['extraData']['provider']);
        $this->setObservations($data['extraData']['observations']);
        $this->setRemoteInstance($data['extraData']['provider']->getCelsiusInstance());
        $data['instance'] = $this->getRemoteInstance();
        $data['stateName'] = StateManager::STATE__CREATED;
        $remoteRequest = $lifecycleHelper->createRequest($request->getOrder(), $request->getOperator(), OrderManager::TYPE__PROVISION, $this->getRemoteInstance());
        $remoteRequest->setOrder($request->getOrder());
        $this->setRemoteRequest($remoteRequest);
        $remoteRequest->setPreviousRequest($request);
        $lifecycleHelper->refresh($remoteRequest);
    }

    /**
     * Set remoteRequest
     *
     * @param  Celsius3\CoreBundle\Document\Request $remoteRequest
     * @return self
     */
    public function setRemoteRequest(\Celsius3\CoreBundle\Document\Request $remoteRequest)
    {
        $this->remoteRequest = $remoteRequest;

        return $this;
    }

    /**
     * Get remoteRequest
     *
     * @return Celsius3\CoreBundle\Document\Request $remoteRequest
     */
    public function getRemoteRequest()
    {
        return $this->remoteRequest;
    }
}