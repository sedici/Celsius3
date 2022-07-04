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

namespace Celsius3\Listener;

use Celsius3\Entity\Order;
use Celsius3\Entity\Request;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Helper\LifecycleHelper;
use Celsius3\Manager\EventManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use FOS\ElasticaBundle\Persister\ObjectPersisterInterface;

class OrderListener
{
    /**
     * @var LifecycleHelper
     */
    private $lifecycleHelper;
    /**
     * @var InstanceHelper
     */
    private $instanceHelper;
    /**
     * @var ObjectPersisterInterface
     */
    private $objectPersister;

    public function __construct
    (
        LifecycleHelper $lifecycleHelper,
        InstanceHelper $instanceHelper,
        ObjectPersisterInterface $objectPersister
    ) {
        $this->lifecycleHelper = $lifecycleHelper;
        $this->instanceHelper = $instanceHelper;
        $this->objectPersister = $objectPersister;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof Order) {
            $entity->getOriginalRequest()->setOrder($entity);
        }
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof Request) {
            $this->lifecycleHelper->createEvent(
                EventManager::EVENT__CREATION,
                $entity,
                $entity->getInstance()
            );

            // Update elasticsearch index
            $this->objectPersister->insertOne($entity);
        }
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof Order) {
            $instance = $this->instanceHelper->getSessionInstance();
            $request = $entity->getRequest($instance);
            if ($request !== null) {
                // Update elasticsearch index
                $this->objectPersister->replaceOne($request);
            }
        }
    }

}
