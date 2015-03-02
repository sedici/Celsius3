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

namespace Celsius3\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Manager\StateManager;
use Doctrine\ORM\Query\Expr\Join;

class StateRepository extends EntityRepository
{

    public function countOrders(Instance $instance = null, BaseUser $user = null, $orderType = null)
    {
        $types = StateManager::$stateTypes;
        $qb = $this->createQueryBuilder('s')
                ->select('s.type, COUNT(s.id) as c')
                ->andWhere('s.isCurrent = true')
                ->andWhere('s.type IN (:types) OR s.type IS NULL')
                ->groupBy('s.type')
                ->setParameter('types', $types);
        
        if (!is_null($orderType)) {
            $qb = $qb->leftJoin('s.request','r')
                    ->andWhere('r.type = :orderType')
                    ->setParameter('orderType', $orderType);
        }
        
        if (!is_null($instance)) {
            $qb = $qb->andWhere('s.instance = :instance')
                    ->setParameter('instance', $instance);
        }

        if (!is_null($user)) {
            $qb = $qb->andWhere('(s.operator = :user)')
                    ->setParameter('user', $user);
        }
        
        $result = array();
        foreach ($qb->getQuery()->getResult() as $type) {
            $result[$type['type']] = intval($type['c']);
        }
        
        $states = StateManager::$stateTypes;
        foreach ($states as $state) {
            if (!array_key_exists($state, $result)) {
                $result[$state] = 0;
            }
        }

        return $result;
    }

    public function findOrdersPerStatePerInstance($state)
    {
        return $this->createQueryBuilder('s')
                        ->select('IDENTITY(s.instance), COUNT(s.id) as c')
                        ->where('s.type = :state_type')
                        ->andWhere('s.isCurrent = true')
                        ->groupBy('s.instance')
                        ->addGroupBy('s.type')
                        ->setParameter('state_type', $state)
                        ->getQuery()
                        ->getResult();
    }

    public function findTotalOrdersPerInstance()
    {
        return $this->createQueryBuilder('s')
                        ->select('IDENTITY(s.instance), COUNT(s.id) as c')
                        ->where('s.isCurrent = true')
                        ->groupBy('s.instance')
                        ->getQuery()
                        ->getResult();
    }

    public function findRequestsStateCountFor($instance, $type, $initialYear, $finalYear)
    {
        $qb = $this->createQueryBuilder('s')
                ->select('s.type stateType')
                ->addSelect('COUNT(r.id) requestsCount')
                ->addSelect('(SUM(md.endPage) - SUM(md.startPage)) pages')
                ->innerJoin('s.request', 'r')
                ->innerJoin('r.order', 'o')
                ->innerJoin('o.materialData', 'md')
                ->andWhere('s.instance = :instance')->setParameter('instance', $instance)
                ->andWhere('s.type <> :stateType')->setParameter('stateType', 'annulled')
                ->andWhere('r.type = :type')->setParameter('type', $type)
                ->groupBy('stateType');

        if ($initialYear === $finalYear) {
            $qb = $qb->addSelect('YEAR(s.createdAt) year')
                    ->addSelect('MONTH(s.createdAt) axisValue')
                    ->andWhere('YEAR(s.createdAt) = :year')->setParameter('year', $initialYear)
                    ->addGroupBy('year');
        } else if ($initialYear < $finalYear) {
            $qb = $qb->addSelect('YEAR(s.createdAt) axisValue')
                            ->andHaving('axisValue >= :initialYear')->setParameter('initialYear', $initialYear)
                            ->andHaving('axisValue <= :finalYear')->setParameter('finalYear', $finalYear);
        }

        $qb = $qb->addGroupBy('axisValue')
                ->orderBy('axisValue', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function getYears($instance)
    {
        return $this->createQueryBuilder('s')
                        ->select('DISTINCT(YEAR(s.createdAt)) year')
                        ->where('s.instance = :instance')->setParameter('instance', $instance)
                        ->andWhere('s.type <> :stateType')->setParameter('stateType', 'annulled')
                        ->groupBy('year')
                        ->orderBy('year', 'ASC')
                        ->getQuery()->getResult();
    }

    public function findRequestsDestinyDistributionFor($instance, $type, $initialYear, $finalYear)
    {
        $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
        $rsm->addScalarResult('countryId', 'countryId')
                ->addScalarResult('countryName', 'countryName')
                ->addScalarResult('stateType', 'stateType')
                ->addScalarResult('year', 'year')
                ->addScalarResult('requestsCount', 'requestsCount');
        return $this->getEntityManager()
                        ->createNativeQuery('SELECT c.id countryId, c.name countryName, YEAR(s.createdAt) year, s.type stateType, COUNT(r.id) requestsCount'
                                . ' FROM state s'
                                . ' INNER JOIN request r ON r.id = s.request_id'
                                . ' INNER JOIN event e ON e.request_id = r.id'
                                . ' INNER JOIN provider p ON p.id = e.provider_id'
                                . ' INNER JOIN country c ON c.id = p.country_id'
                                . ' WHERE s.instance_id = :instance'
                                . ' AND r.type = :type'
                                . ' AND s.type <> :stateType'
                                . ' GROUP BY c.id, stateType'
                                . ' HAVING year >= :initialYear AND year <= :finalYear'
                                . ' ORDER BY requestsCount ASC', $rsm)
                        ->setParameter('instance', $instance)
                        ->setParameter('type', $type)
                        ->setParameter('stateType', 'annulled')
                        ->setParameter('initialYear', $initialYear)
                        ->setParameter('finalYear', $finalYear)
                        ->getResult();
    }
}
