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

namespace Celsius3\CoreBundle\Entity\Event;

use Doctrine\ORM\Mapping as ORM;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Entity\Request;
use Celsius3\NotificationBundle\Entity\Notifiable;
use Celsius3\NotificationBundle\Manager\NotificationManager;

/**
 * @ORM\Entity
 */
class CancelEvent extends SingleInstanceEvent implements Notifiable
{
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $cancelledByUser = false;

    public function getEventType()
    {
        return 'cancel';
    }

    public function applyExtraData(Request $request, array $data, LifecycleHelper $lifecycleHelper, $date)
    {
        if (array_key_exists('remoterequest', $data['extraData'])) {
            $data['extraData']['remoterequest']->setCancelled(true);
            $lifecycleHelper->refresh($data['extraData']['remoterequest']);
        }

        $this->setCancelledByUser($data['extraData']['cancelled_by_user']);
        $this->setObservations($data['extraData']['observations']);

        $lifecycleHelper->getEventManager()->cancelRequests($data['extraData']['sirequests'], $data['extraData']['httprequest']);
        $lifecycleHelper->getEventManager()->cancelRequests($data['extraData']['mirequests'], $data['extraData']['httprequest']);
    }

    public function notify(NotificationManager $manager)
    {
        $manager->notifyEvent($this, 'cancel');
    }

    public function getCancelledByUser()
    {
        return $this->cancelledByUser;
    }

    public function setCancelledByUser($cancelledByUser)
    {
        $this->cancelledByUser = $cancelledByUser;

        return $this;
    }
}
