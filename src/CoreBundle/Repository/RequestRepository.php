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

use Celsius3\CoreBundle\Entity\Event\MultiInstanceRequestEvent;
use Celsius3\CoreBundle\Entity\Event\SingleInstanceRequestEvent;
use Celsius3\CoreBundle\Manager\EventManager;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * RequestRepository.
 */
class RequestRepository extends BaseRepository
{
    public function countActiveUsersFor($instance, $type, $initialYear, $finalYear)
    {
        $qb = $this->createQueryBuilder('request');

        if ($initialYear === $finalYear) {
            $qb = $qb->select('MONTH(request.created_at) axisValue')
                            ->andWhere('YEAR(request.created_at) = :year')->setParameter('year', $initialYear);
        } elseif ($initialYear < $finalYear) {
            $qb = $qb->select('YEAR(request.created_at) axisValue')
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
            $qb = $qb->andWhere('YEAR(r.created_at) = :year')->setParameter('year', $initialYear);
        } elseif ($initialYear < $finalYear) {
            $qb = $qb->andWhere('YEAR(r.created_at) >= :initialYear')->setParameter('initialYear', $initialYear)
                            ->andWhere('YEAR(r.created_at) <= :finalYear')->setParameter('finalYear', $finalYear);
        }

        return $qb->getQuery()->getResult();
    }

    public function findRequestsDelay($instance, $type, $initialYear, $finalYear, $delayType)
    {
        $dql = "SELECT YEAR(r.created_at) cYear, DATEDIFF(sB.created_at,sA.created_at) delay, COUNT(r.id) rCount
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
            $dql .= 'AND YEAR(r.created_at) = :year ';
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

    public function getInteractionOfInstitutionWithInstance($instance, $institutions, $initialYear = null, $finalYear = null)
    {
        $qb = $this->createQueryBuilder('r');

        $qb = $qb->select('YEAR(r.created_at) year')
                ->addSelect('s.type st')
                ->addSelect('COUNT(r.id) c')
                ->innerJoin('r.owner', 'o', Join::WITH, $qb->expr()->in('o.institution', $institutions))
                ->innerJoin('r.states', 's')
                ->groupBy('year')
                ->addGroupBy('s.type');

        if (!is_null($instance)) {
            $qb = $qb->andWhere('r.instance = :instance')->setParameter('instance', $instance);
        }

        if ($initialYear && $finalYear && $initialYear <= $finalYear) {
            $qb = $qb->andWhere('YEAR(s.created_at) >= :initialYear')->setParameter('initialYear', $initialYear)
                ->andWhere('YEAR(s.created_at) <= :finalYear')->setParameter('finalYear', $finalYear);
        }

        return $qb->getQuery()->getArrayResult();
    }

    private function getRequestsEventsForInteraction($instance, $institutions, $initialYear, $finalYear) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('year', 'year');
        $rsm->addScalarResult('st', 'st');
        $rsm->addScalarResult('c', 'c');

        $sql = 'SELECT YEAR(r.created_at) year, s.type st, COUNT(e.id) c '
            . 'FROM event e '
            . 'INNER JOIN request r ON e.request_id = r.id '
            . 'LEFT JOIN state s ON e.state_id = s.id '
            . 'WHERE e.instance_id = :instance_id AND e.type IN (:types) AND e.provider_id IN (:institutions) '
        ;
        $sql .= 'AND YEAR(e.created_at) >= :initialYear AND YEAR(e.created_at) <= :finalYear ';
        $sql .= 'GROUP BY year, st';

        $query = $this->getEntityManager()
            ->createNativeQuery($sql, $rsm);

        $query->setParameter('instance_id', $instance->getId());
        $query->setParameter('institutions', $institutions);
        $query->setParameter('initialYear', $initialYear);
        $query->setParameter('finalYear', $finalYear);
        $query->setParameter('types', [
            EventManager::EVENT__MULTI_INSTANCE_REQUEST,
            EventManager::EVENT__SINGLE_INSTANCE_REQUEST
        ]);

        return $query->getResult();
    }

    private function getReceiveEventsForInteraction($instance, $institutions, $initialYear, $finalYear) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('year', 'year');
        $rsm->addScalarResult('c', 'c');

        $sql = 'SELECT YEAR(r.created_at) year, COUNT(e.id) c '
            . 'FROM event e '
            . 'INNER JOIN request r ON e.request_id = r.id '
            . 'INNER JOIN event re ON e.request_event_id = re.id '
            . 'WHERE e.instance_id = :instance_id AND e.type IN (:types) AND re.provider_id IN (:institutions) '
            . 'AND YEAR(e.created_at) >= :initialYear AND YEAR(e.created_at) <= :finalYear '
            . 'GROUP BY year '
        ;

        $query = $this->getEntityManager()
            ->createNativeQuery($sql, $rsm);

        $query->setParameter('instance_id', $instance->getId());
        $query->setParameter('institutions', $institutions);
        $query->setParameter('initialYear', $initialYear);
        $query->setParameter('finalYear', $finalYear);
        $query->setParameter('types', [
            EventManager::EVENT__MULTI_INSTANCE_RECEIVE,
            EventManager::EVENT__SINGLE_INSTANCE_RECEIVE
        ]);

        return $query->getResult();
    }

    public function getInteractionOfInstanceWithInstitution($instance, $institutions, $initialYear, $finalYear)
    {
        $req = $this->getRequestsEventsForInteraction($instance, $institutions, $initialYear, $finalYear);
        $rec = $this->getReceiveEventsForInteraction($instance, $institutions, $initialYear, $finalYear);

        $rec = array_map(function($r) {
            $r['st'] = 'received';
            return $r;
        }, $rec);

        return array_merge($req,$rec);
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
