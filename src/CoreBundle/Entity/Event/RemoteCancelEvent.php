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

use Celsius3\CoreBundle\Entity\Request;
use Celsius3\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Manager\EventManager;
use Celsius3\NotificationBundle\Entity\Notifiable;
use Celsius3\NotificationBundle\Manager\NotificationManager;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Celsius3\CoreBundle\Repository\BaseRepository")
 */
class RemoteCancelEvent extends MultiInstanceEvent implements Notifiable
{
    public function getEventType(): string
    {
        return 'remotecancel';
    }

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date): void
    {
        $data['extraData']['request']->setCancelled(true);
        $lifecycleHelper->refresh($data['extraData']['request']);
        $this->setRemoteInstance($data['extraData']['request']->getRemoteInstance());
        $lifecycleHelper->createEvent(
            EventManager::EVENT__CANCEL,
            $request->getOrder()->getRequest($this->getRemoteInstance()),
            $this->getRemoteInstance()
        );
    }

    public function notify(NotificationManager $manager): void
    {
        $manager->notifyRemoteEvent($this, 'cancel');
    }

    public function getRemoteNotificationTarget()
    {
        return $this->getRequest()->getOrder()->getRequest($this->getRemoteInstance())->getOperator();
    }
}
