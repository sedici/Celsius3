<?php
/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\CoreBundle\Document\Event;

use Symfony\Component\Validator\Constraints as Assert;
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