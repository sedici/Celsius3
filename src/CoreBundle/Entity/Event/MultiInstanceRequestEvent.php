<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
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

declare(strict_types=1);

namespace Celsius3\CoreBundle\Entity\Event;

use Celsius3\CoreBundle\Entity\Mixin\AnnullableTrait;
use Celsius3\CoreBundle\Entity\Mixin\CancellableTrait;
use Celsius3\CoreBundle\Entity\Mixin\ProviderTrait;
use Celsius3\CoreBundle\Entity\Mixin\ReclaimableTrait;
use Celsius3\CoreBundle\Entity\Request;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Manager\OrderManager;
use Celsius3\CoreBundle\Manager\StateManager;
use Celsius3\NotificationBundle\Entity\Notifiable;
use Celsius3\NotificationBundle\Manager\NotificationManager;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Celsius3\CoreBundle\Repository\BaseRepository")
 */
class MultiInstanceRequestEvent extends MultiInstanceEvent implements Notifiable
{
    use ReclaimableTrait;
    use CancellableTrait;
    use AnnullableTrait;
    use ProviderTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Celsius3\CoreBundle\Entity\Request", cascade={"persist", "refresh"})
     * @ORM\JoinColumn(name="remote_request_id", referencedColumnName="id")
     */
    private $remoteRequest;

    public function getEventType(): string
    {
        return 'mirequest';
    }

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date): void
    {
        $this->setProvider($data['extraData']['provider']);
        $this->setObservations($data['extraData']['observations']);
        $this->setRemoteInstance($data['extraData']['provider']->findCelsiusInstance());
        $data['instance'] = $this->getRemoteInstance();
        $data['stateName'] = StateManager::STATE__CREATED;
        $remote_request = $lifecycleHelper->createRequest(
            $request->getOrder(),
            $request->getOperator(),
            OrderManager::TYPE__PROVISION,
            $this->getRemoteInstance(),
            $request->getOperator()
        );
        $remote_request->setOrder($request->getOrder());
        $this->setRemoteRequest($remote_request);
        $remote_request->setPreviousRequest($request);
        $lifecycleHelper->refresh($remote_request);
        $remote_creation = $remote_request->getState(StateManager::STATE__CREATED);
        $remote_creation->setRemoteEvent($this);

        /* En caso de que se esté haciendo una segunda petición a la misma instancia,
           se vuelve a dejar el estado actual como creado */
        $current_state = $remote_request->getCurrentState();
        if ($current_state->getId() !== $remote_creation->getId()) {
            $remote_creation->setCurrent(true);
            $current_state->setCurrent(false);
            $lifecycleHelper->refresh($current_state);
        }
    }

    public function getRemoteRequest(): Request
    {
        return $this->remoteRequest;
    }

    public function setRemoteRequest(Request $remoteRequest): self
    {
        $this->remoteRequest = $remoteRequest;

        return $this;
    }

    public function notify(NotificationManager $manager): void
    {
        $manager->notifyEvent($this, 'request');
    }

    public function getReclaimed(): bool
    {
        return $this->reclaimed;
    }

    public function getCancelled(): bool
    {
        return $this->cancelled;
    }

    public function getAnnulled(): bool
    {
        return $this->annulled;
    }
}
