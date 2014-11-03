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

namespace Celsius3\CoreBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Celsius3\CoreBundle\Entity\Counter;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Entity\Order;

class CounterListener
{

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        if ($entity instanceof Order) {
            $em->beginTransaction();
            try {
                $code = $em->createQueryBuilder('Celsius3CoreBundle:Counter')
                            ->findOneBy(array(
                                'name' => $entity->getOriginalRequest()->getInstance()->getId(),
                            ));
                $entity->setCode($code->getValue());
                $em->persist($entity);
                
                $code->setValue($code->getValue() + 1);
                $em->persist($code);
                
                $em->flush();
                
                $em->commit();
            } catch(Exception $e) {
                $em->rollback();
                throw $e;
            }
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        if ($entity instanceof Instance) {
            $counter = new Counter();
            $counter->setName($entity->getId());
            $counter->setValue(1);
            $em->persist($counter);
            $em->flush($counter);
        }
    }
}