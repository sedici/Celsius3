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

class StateRepository extends EntityRepository
{

    public function countOrders(Instance $instance = null, BaseUser $user = null, $orderType = null)
    {
        $types = StateManager::$stateTypes;
        $qb = $this->createQueryBuilder('s')
                ->select('s.type, COUNT(s.id) as c')
                ->leftJoin('s.request', 'r')
                ->andWhere('s.isCurrent = true')
                ->groupBy('s.type');

        if (!is_null($orderType)) {
            $qb = $qb->andWhere('r.type = :orderType')
                    ->setParameter('orderType', $orderType);
        }

        if (!is_null($instance)) {
            $qb = $qb->andWhere('s.instance = :instance')
                    ->setParameter('instance', $instance);
        }

        if (!is_null($user)) {
            $qb = $qb->andWhere('(r.operator = :user)')
                    ->setParameter('user', $user);
        }

        $result = array();
        foreach ($qb->getQuery()->getResult() as $type) {
            $result[$type['type']] = intval($type['c']);
        }

        foreach ($types as $state) {
            if (!array_key_exists($state, $result)) {
                $result[$state] = 0;
            }
        }

        // Se cuentan aquellos que tienen busquedas pendientes
        $qb2 = $this->createQueryBuilder('s')
                        ->select('COUNT(s.id) as c')
                        ->andWhere('s.isCurrent = true')
                        ->andWhere('s.type = :type')
                        ->andWhere('s.searchPending = true')
                        ->groupBy('s.type')
                        ->setParameter('type', StateManager::STATE__REQUESTED)
                        ->getQuery()->getResult();

        if (count($qb2) > 0) {
            $result[StateManager::STATE__SEARCHED] += intval($qb2['c']);
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
        $qb = $this->createQueryBuilder('s');

        if (!is_null($instance)) {
            $qb = $qb->andWhere('s.instance = :instance')->setParameter('instance', $instance);
        }

        $qb = $qb->select('s.type stateType')
                ->addSelect('COUNT(r.id) requestsCount')
                ->addSelect('SUM(f.pages) pages')
                ->innerJoin('s.request', 'r')
                ->innerJoin('r.order', 'o')
                ->innerJoin('o.materialData', 'md')
                ->leftJoin('r.files', 'f')
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

    public function findRequestsDestinyDistributionFor($instance, $type, $initialYear, $finalYear)
    {
        $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
        $rsm->addScalarResult('countryId', 'countryId')
                ->addScalarResult('countryName', 'countryName')
                ->addScalarResult('stateType', 'stateType')
                ->addScalarResult('year', 'year')
                ->addScalarResult('requestsCount', 'requestsCount');

        $sql = 'SELECT c.id countryId, c.name countryName, YEAR(s.createdAt) year, s.type stateType, COUNT(r.id) requestsCount'
                . ' FROM state s'
                . ' INNER JOIN request r ON r.id = s.request_id'
                . ' INNER JOIN event e ON e.request_id = r.id'
                . ' INNER JOIN provider p ON p.id = e.provider_id'
                . ' INNER JOIN country c ON c.id = p.country_id'
                . ' WHERE ';

        if (!is_null($instance)) {
            $sql .= ' s.instance_id = :instance'
                    . ' AND';
        }

        $sql .= ' r.type = :type'
                . ' AND s.type <> :stateType'
                . ' GROUP BY c.id, stateType'
                . ' HAVING year >= :initialYear AND year <= :finalYear'
                . ' ORDER BY requestsCount ASC';

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        if (!is_null($instance)) {
            $query = $query->setParameter('instance', $instance);
        }

        return $query->setParameter('type', $type)
                ->setParameter('stateType', 'annulled')
                ->setParameter('initialYear', $initialYear)
                ->setParameter('finalYear', $finalYear)
                ->getResult();
    }

}
