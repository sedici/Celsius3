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

namespace Celsius3\NotificationBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Celsius3\NotificationBundle\Manager\NotificationManager;
use Celsius3\NotificationBundle\Entity\Notifiable;

class NotificationListener
{
    private $notification_manager;

    public function __construct(NotificationManager $notification_manager)
    {
        $this->notification_manager = $notification_manager;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Notifiable) {
            $entity->notify($this->notification_manager);
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->postPersist($args);
    }
}
