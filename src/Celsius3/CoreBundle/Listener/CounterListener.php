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

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Celsius3\CoreBundle\Document\Counter;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Document\Order;

class CounterListener
{

    public function prePersist(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();
        $dm = $args->getDocumentManager();

        if ($document instanceof Order) {
            $document->setCode($dm->createQueryBuilder('Celsius3CoreBundle:Counter')
                            ->findAndUpdate()->refresh(true)
                            ->field('name')
                            ->equals($document->getOriginalRequest()->getInstance()->getId())
                            ->field('value')->inc(1)->getQuery()
                            ->execute()->getValue());
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();
        $dm = $args->getDocumentManager();

        if ($document instanceof Instance) {
            $counter = new Counter();
            $counter->setName($document->getId());
            $counter->setValue(1);
            $dm->persist($counter);
            $dm->flush();
        }
    }

}