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
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;


#[AsDoctrineListener(Events::prePersist)]
#[AsDoctrineListener(Events::postPersist)]
#[AsDoctrineListener(Events::postUpdate)]
class OrderListener
{

    public function __construct
    (
        private LifecycleHelper $lifecycleHelper,
        private InstanceHelper $instanceHelper
    ) {}


    // #[AsDoctrineListener(Events::prePersist)]
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Order) return;

        $entity->getOriginalRequest()->setOrder($entity);
    }


    // #[AsDoctrineListener(Events::postPersist)]
    public function postPersist(PostPersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Request) return;

        // if (!$entity instanceof Order) return;

        // $request = $entity->getOriginalRequest();
        // if ($entity === null) return;
        
        $instance = $entity->getInstance();
        
        $event = $this->lifecycleHelper->createEvent(
            EventManager::EVENT__CREATION,
            $entity,
            $instance,
        );
        // throw new \Exception((string)var_dump($event));
        
        // Update elasticsearch index
        // $this->objectPersister->insertOne($entity);
    }


    // #[AsDoctrineListener(Events::postUpdate)]
    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Order) return;

        $instance = $this->instanceHelper->getSessionInstance();
        $request = $entity->getRequest($instance);
        if ($request !== null) {
            // Update elasticsearch index
            //  $this->objectPersister->replaceOne($request);
        }
    }
}
