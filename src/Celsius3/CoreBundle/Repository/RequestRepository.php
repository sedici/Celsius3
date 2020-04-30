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

use Doctrine\ORM\Query\Expr\Join;

/**
 * RequestRepository.
 */
class RequestRepository extends BaseRepository
{
    public function countActiveUsersFor($instance, $type, $initialYear, $finalYear)
    {
        $qb = $this->createQueryBuilder('request');

        if ($initialYear === $finalYear) {
            $qb = $qb->select('MONTH(request.createdAt) axisValue')
                            ->andWhere('YEAR(request.createdAt) = :year')->setParameter('year', $initialYear);
        } elseif ($initialYear < $finalYear) {
            $qb = $qb->select('YEAR(request.createdAt) axisValue')
                            ->andHaving('axisValue >= :initialYear')->setParameter('initialYear', $initialYear)
                            ->andHaving('axisValue <= :finalYear')->setParameter('finalYear', $finalYear);
        }

        if (!is_null($instance)) {
            $qb = $qb->andWhere('request.instance = :instance')->setParameter('instance', $instance);
        }

        $qb = $qb->addSelect('COUNT(DISTINCT request.owner) activeUsers')
                ->andWhere('request.type = :type')->setParameter('type', $type)
                ->groupBy('axisValue')
                ->orderBy('axisValue', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function findRequestsNumberByPublicationYearFor($instance, $type, $initialYear, $finalYear)
    {
        $qb = $this->createQueryBuilder('r');

        if (!is_null($instance)) {
            $qb = $qb->andWhere('r.instance = :instance')->setParameter('instance', $instance);
        }

        $qb = $qb->select('md.year materialDataYear')
                ->addSelect('COUNT(md.id) materialDataCount')
                ->innerJoin('r.order', 'o')
                ->innerJoin('o.materialData', 'md')
                ->andWhere('r.type = :type')->setParameter('type', $type)
                ->groupBy('materialDataYear')
                ->orderBy('materialDataYear', 'ASC')
                ->having('materialDataYear >= :iYear')->setParameter('iYear', 1500)
                ->andHaving('materialDataYear <= :fYear')->setParameter('fYear', date('Y'));

        if ($initialYear === $finalYear) {
            $qb = $qb->andWhere('YEAR(r.createdAt) = :year')->setParameter('year', $initialYear);
        } elseif ($initialYear < $finalYear) {
            $qb = $qb->andWhere('YEAR(r.createdAt) >= :initialYear')->setParameter('initialYear', $initialYear)
                            ->andWhere('YEAR(r.createdAt) <= :finalYear')->setParameter('finalYear', $finalYear);
        }

        return $qb->getQuery()->getResult();
    }

    public function findRequestsDelay($instance, $type, $initialYear, $finalYear, $delayType)
    {
        $dql = "SELECT YEAR(r.createdAt) cYear, DATEDIFF(sB.createdAt,sA.createdAt) delay, COUNT(r.id) rCount
                FROM Celsius3\CoreBundle\Entity\Request r
                JOIN Celsius3\CoreBundle\Entity\State sA WITH r = sA.request
                LEFT JOIN Celsius3\CoreBundle\Entity\State sB WITH sA.request = sB.request
                WHERE r NOT IN (
                    SELECT rs
                    FROM Celsius3\CoreBundle\Entity\Request rs
                    JOIN Celsius3\CoreBundle\Entity\State st WITH rs = st.request
                    WHERE st.type = 'annulled' OR st.type = 'cancelled'
                )";
        if (!is_null($instance)) {
            $dql .= ' AND r.instance = :instance';
        }

        $dql .= ' AND r.type = :type ';

        if ($delayType === 'totalDelay') {
            $dql .= "AND sA.type = 'created'
                AND sB.type = 'received' ";
        } elseif ($delayType === 'locationDelay') {
            $dql .= "AND sA.type = 'created'
                AND sB.type = 'requested' ";
        } elseif ($delayType === 'responseDelay') {
            $dql .= "AND sA.type = 'requested'
                AND sB.type = 'received' ";
        } else {
            $dql .= "AND sA.type = 'created'
                AND sB.type = 'received' ";
        }

        if ($initialYear === $finalYear) {
            $dql .= 'AND YEAR(r.createdAt) = :year ';
        }

        $dql .= 'GROUP BY cYear,delay ';

        if ($initialYear < $finalYear) {
            $dql .= 'HAVING cYear >= :rAyear
                     AND cYear <= :rByear ';
        }

        $dql .= 'ORDER BY cYear,delay';

        $query = $this->getEntityManager()->createQuery($dql);

        if (!is_null($instance)) {
            $query = $query->setParameter('instance', $instance);
        }

        $query = $query->setParameter('type', $type);

        if ($initialYear === $finalYear) {
            $query = $query->setParameter('year', $initialYear);
        }

        if ($initialYear < $finalYear) {
            $query = $query->setParameter('rAyear', $initialYear)
                    ->setParameter('rByear', $finalYear);
        }

        return $query->getResult();
    }

    public function getInteractionOfInstitutionWithInstance($instance, $institutions, $initialYear, $finalYear)
    {
        $qb = $this->createQueryBuilder('r');

        $qb = $qb->select('YEAR(s.createdAt) year')
                ->addSelect('s.type st')
                ->addSelect('COUNT(r.id) c')
                ->innerJoin('r.owner', 'o', Join::WITH, $qb->expr()->in('o.institution', $institutions))
                ->innerJoin('r.states', 's')
                ->groupBy('year')
                ->addGroupBy('s.type');

        if (!is_null($instance)) {
            $qb = $qb->andWhere('r.instance = :instance')->setParameter('instance', $instance);
        }

        if ($initialYear < $finalYear) {
            $qb = $qb->andWhere('YEAR(s.createdAt) >= :initialYear')->setParameter('initialYear', $initialYear)
                ->andWhere('YEAR(s.createdAt) <= :finalYear')->setParameter('finalYear', $finalYear);
        }

        return $qb->getQuery()->getArrayResult();
    }

    public function getInteractionOfInstanceWithInstitution($instance, $institutions, $initialYear, $finalYear)
    {
        $dql = 'SELECT YEAR(s.createdAt) year, s.type st, COUNT(s.request) c '
                .'FROM Celsius3CoreBundle:Event\SingleInstanceRequestEvent e '
                .'JOIN e.request r WITH e.provider IN (:institutions) '
                .'JOIN r.states s ';

        $dql .= 'WHERE e.instance = :instance ';

        if ($initialYear < $finalYear) {
            $dql .= 'AND YEAR(s.createdAt) >= :initialYear AND YEAR(s.createdAt) <= :finalYear ';
        }

        $dql .= 'GROUP BY year, s.type';

        $query = $this->getEntityManager()
                ->createQuery($dql);

        $query->setParameter('institutions', $institutions);
        if (!is_null($instance)) {
            $query->setParameter('instance', $instance->getId());
        }
        if ($initialYear < $finalYear) {
            $query->setParameter('initialYear', $initialYear);
            $query->setParameter('finalYear', $finalYear);
        }

        return $query->getResult();
    }

    public function findRequestForOrders(array $orders)
    {
        return $this->createQueryBuilder('r')
                    ->where('r.order IN (:orders)')
                    ->setParameter('orders', $orders)
                    ->getQuery()->getResult();
    }

    public function findByOrders($orders)
    {
        return $this->createQueryBuilder('r')
                    ->select('r, s, ow')
                    ->join('r.states', 's')
                    ->join('r.owner', 'ow')
                    ->where('r.order IN (:orders)')
                    ->andWhere('s.current = true')
                    ->setParameter('orders', $orders)
                    ->getQuery()->getResult();
    }
}
