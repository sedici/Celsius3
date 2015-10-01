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
use Doctrine\ORM\Query\Expr\Join;
use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Manager\StateManager;

class StateRepository extends EntityRepository
{

    public function countUserOrders(Instance $instance, BaseUser $user)
    {
        $types = StateManager::$stateTypes;
        $qb = $this->createQueryBuilder('s')
                ->select('s.type, COUNT(s.id) as c')
                ->leftJoin('s.request', 'r')
                ->andWhere('s.isCurrent = true')
                ->andWhere('s.instance = :instance')
                ->setParameter('instance', $instance->getId())
                ->andWhere('(r.owner = :user)')
                ->setParameter('user', $user)
                ->groupBy('s.type');

        $result = array();
        foreach ($qb->getQuery()->getResult() as $type) {
            $result[$type['type']] = intval($type['c']);
        }

        foreach ($types as $state) {
            if (!array_key_exists($state, $result)) {
                $result[$state] = 0;
            }
        }

        return $result;
    }

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
                    ->setParameter('instance', $instance->getId());
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
                ->andWhere('s.instance = :instance')
                ->andWhere('s.isCurrent = true')
                ->andWhere('s.type = :type')
                ->andWhere('s.searchPending = true')
                ->groupBy('s.type')
                ->setParameter('type', StateManager::STATE__REQUESTED)
                ->setParameter('instance', $instance->getId());
        if (!is_null($user)) {
            $qb2 = $qb2->leftJoin('s.request', 'r')
                    ->andWhere('(r.operator = :user)')
                    ->setParameter('user', $user);
        }
        $qb2 = $qb2->getQuery()->getResult();

        if (count($qb2) > 0) {
            $result[StateManager::STATE__SEARCHED] += intval($qb2[0]['c']);
        }

        // Se cuentan como propios los que no tienen administrador
        if (!is_null($user)) {
            $qb3 = $this->createQueryBuilder('s')
                            ->select('COUNT(s.id) as c')
                            ->andWhere('s.instance = :instance')
                            ->andWhere('s.isCurrent = true')
                            ->andWhere('s.type = :type')
                            ->groupBy('s.type')
                            ->setParameter('type', StateManager::STATE__CREATED)
                            ->setParameter('instance', $instance->getId())
                            ->getQuery()->getResult();

            if (count($qb3) > 0) {
                $result[StateManager::STATE__CREATED] = intval($qb3[0]['c']);
            }
        }

        return $result;
    }

    public function findOrdersPerStatesPerInstance(array $states, $request_type = 'search')
    {
        $qb = $this->createQueryBuilder('s');
        return $qb->select('IDENTITY(s.instance), COUNT(s.id) as c')
                        ->innerJoin('s.request', 'r', Join::WITH, $qb->expr()->in('s.type', $states))
                        ->where('s.isCurrent = true')
                        ->andWhere('r.type = :request_type')
                        ->groupBy('s.instance')
                        ->setParameter('request_type', $request_type)
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

    public function getUsersWithPendingRequests($instance, $minDays, $maxDays)
    {
        $today = new \DateTime();

        return $this->createQueryBuilder('s')
                        ->addSelect('ow.id id')
                        ->addSelect('ow.username username')
                        ->addSelect('ow.surname surname')
                        ->addSelect('ow.name name')
                        ->addSelect('ow.email email')
                        ->addSelect('md.title request')
                        ->addSelect('DATEDIFF(:today,es.createdAt) as days')->setParameter('today', $today)
                        ->leftJoin('s.request', 'r')
                        ->leftJoin('r.owner', 'ow')
                        ->leftJoin('r.order', 'or')
                        ->leftJoin('or.materialData', 'md')
                        ->leftJoin('r.events', 'es')
                        ->andWhere('s.type = :type')->setParameter('type', 'received')
                        ->andWhere('s.isCurrent = :current')->setParameter('current', TRUE)
                        ->andWhere('s.instance = :instance')->setParameter('instance', $instance)
                        ->andWhere('es INSTANCE OF Celsius3CoreBundle:Event\SingleInstanceReceiveEvent')
                        ->andWhere('ow.wrongEmail = false')
                        ->orderBy('ow.id')
                        ->andHaving('days >= :minDays')->setParameter('minDays', $minDays)
                        ->andHaving('days <= :maxDays')->setParameter('maxDays', $maxDays)
                        ->getQuery()->getResult();
    }

    public function countUsersWithPendingRequests($instance, $minDays, $maxDays)
    {
        $today = new \DateTime();

        return $this->createQueryBuilder('s')
                        ->addSelect('ow.id')
                        ->addSelect('ow.username')
                        ->addSelect('ow.surname')
                        ->addSelect('ow.name')
                        ->addSelect('count(md.id) requestsCount')
                        ->addSelect('DATEDIFF(:today,es.createdAt) as days')->setParameter('today', $today)
                        ->leftJoin('s.request', 'r')
                        ->leftJoin('r.order', 'or')
                        ->leftJoin('or.materialData', 'md')
                        ->leftJoin('r.events', 'es')
                        ->leftJoin('r.owner', 'ow')
                        ->andWhere('s.type = :type')->setParameter('type', 'received')
                        ->andWhere('s.isCurrent = :current')->setParameter('current', TRUE)
                        ->andWhere('s.instance = :instance')->setParameter('instance', $instance)
                        ->andWhere('es INSTANCE OF Celsius3CoreBundle:Event\SingleInstanceReceiveEvent')
                        ->andWhere('ow.wrongEmail = :wrongEmail')->setParameter('wrongEmail', FALSE)
                        ->orderBy('ow.id')
                        ->groupBy('ow.id')
                        ->andHaving('days >= :minDays')->setParameter('minDays', $minDays)
                        ->andHaving('days <= :maxDays')->setParameter('maxDays', $maxDays)
                        ->getQuery()->getResult();
    }

}
