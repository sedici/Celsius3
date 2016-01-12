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

namespace Celsius3\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Entity\Order;
use Celsius3\CoreBundle\Entity\JournalType;

class EventRepository extends EntityRepository
{
    public function findSimilarSearches(Order $order, Instance $instance)
    {
        if ($order->getMaterialData() instanceof JournalType) {
            $qb = $this->getEntityManager()->getRepository('Celsius3CoreBundle:Event\\SearchEvent')
                ->createQueryBuilder('s')
                ->select('s, c, r, o')
                ->join('s.catalog', 'c')
                ->join('s.request', 'r')
                ->join('r.order', 'o')
                ->leftJoin('Celsius3CoreBundle:JournalType', 'md', 'WITH', 'o.materialData = md.id')
                ->leftJoin('md.journal', 'j')
                ->where('j.name = :name')
                ->orWhere('md.other = :name')
                ->andWhere('o.id <> :order_id')
                ->setParameter('order_id', $order->getId());
            if (is_null($order->getMaterialData()->getJournal())) {
                $qb = $qb->setParameter('name', $order->getMaterialData()->getOther());
            } else {
                $qb = $qb->setParameter('name', $order->getMaterialData()->getJournal()->getName());
            }
        } else {
            $qb = $this->getEntityManager()->getRepository('Celsius3CoreBundle:Event\\SearchEvent')
                ->createQueryBuilder('s')
                ->select('s, c, r, o')
                ->join('s.catalog', 'c')
                ->join('s.request', 'r')
                ->join('r.order', 'o')
                ->join('o.materialData', 'md')
                ->where('md.title = :name')
                ->andWhere('o.id <> :order_id')
                ->setParameter('order_id', $order->getId())
                ->setParameter('name', $order->getMaterialData()->getTitle());
        }

        return $qb->getQuery()->execute();
    }

}
