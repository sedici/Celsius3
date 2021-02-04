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

use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Manager\StateManager;
use Doctrine\ORM\Query\Expr\Join;

/**
 * StateRepository.
 */
class StateRepository extends BaseRepository
{
    public function countUserOrders(Instance $instance, BaseUser $user)
    {
        $types = StateManager::$stateTypes;
        $qb = $this->createQueryBuilder('s')
            ->select('s.type, COUNT(s.id) as c')
            ->leftJoin('s.request', 'r')
            ->andWhere('s.current = true')
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
            ->where('s.current = true')
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

        // Se cuentan los pedidos solicitados que tienen busquedas pendientes
        // para restarlos a los pedidos solicitados
        $qb4 = $this->createQueryBuilder('s')
            ->select('s.type, COUNT(s.id) as c')
            ->leftJoin('s.request', 'r')
            ->where('s.current = :current')
            ->setParameter('current', true)
            ->andWhere('s.type = :type')
            ->setParameter('type', StateManager::STATE__REQUESTED)
            ->andWhere('s.searchPending = :pending')
            ->setParameter('pending', true)
            ->groupBy('s.type');

        if (!is_null($instance)) {
            $qb4->andWhere('s.instance = :instance')
                ->setParameter('instance', $instance->getId());
        }
        if (!is_null($user)) {
            $qb4->andWhere('(r.operator = :user)')
                ->setParameter('user', $user);
        }

        $pending = $qb4->getQuery()->getOneOrNullResult();
        if (!is_null($pending)) {
            $result[$pending['type']] -= $pending['c'];
        }

        // Se cuentan aquellos que tienen busquedas pendientes
        $qb2 = $this->createQueryBuilder('s')
            ->select('COUNT(s.id) as c')
            ->andWhere('s.instance = :instance')
            ->andWhere('s.current = true')
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
                ->andWhere('s.current = true')
                ->andWhere('s.type = :type')
                ->groupBy('s.type')
                ->setParameter('type', StateManager::STATE__CREATED)
                ->setParameter('instance', $instance->getId());

            if (!is_null($orderType)) {
                $qb3 = $qb3->leftJoin('s.request', 'r')
                    ->andWhere('r.type = :orderType')
                    ->setParameter('orderType', $orderType);
            }

            $qb3 = $qb3->getQuery()->getResult();

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
            ->where('s.current = true')
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
            ->where('s.current = true')
            ->groupBy('s.instance')
            ->getQuery()
            ->getResult();
    }

    public function findRequestsStateCountFor($instance, $type, $initialYear, $finalYear)
    {
        $qb = $this->createQueryBuilder('s');

        if (!is_null($instance)) {
            $qb = $qb->andWhere('r.instance = :instance')->setParameter('instance', $instance);
        }

        $qb = $qb->select('s.type stateType')
            ->addSelect('COUNT(r.id) requestsCount')
            ->join('s.request', 'r')
            ->andWhere('r.type = :type')->setParameter('type', $type)
            ->groupBy('stateType');

        if ($initialYear === $finalYear) {
            $qb = $qb->addSelect('YEAR(r.deletedAt) year')
                ->addSelect('MONTH(r.deletedAt) axisValue')
                ->andWhere('YEAR(r.deletedAt) = :year')->setParameter('year', $initialYear)
                ->addGroupBy('year');
        } elseif ($initialYear < $finalYear) {
            $qb = $qb->addSelect('YEAR(r.deletedAt) axisValue')
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

        $sql = 'SELECT c.id countryId, c.name countryName, YEAR(s.deletedAt) year, s.type stateType, COUNT(r.id) requestsCount'
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
            ->select('ow.id id')
            ->addSelect('ow.username username')
            ->addSelect('ow.surname surname')
            ->addSelect('ow.name name')
            ->addSelect('ow.email email')
            ->addSelect('md.title request')
            ->addSelect('ANY_VALUE(DATEDIFF(:today,es.deletedAt)) as days')->setParameter('today', $today)
            ->leftJoin('s.request', 'r')
            ->leftJoin('r.owner', 'ow')
            ->leftJoin('r.order', 'or')
            ->leftJoin('or.materialData', 'md')
            ->leftJoin('r.events', 'es')
            ->andWhere('s.type = :type')->setParameter('type', 'received')
            ->andWhere('s.current = :current')->setParameter('current', true)
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
            ->select('ow.id')
            ->addSelect('ow.username')
            ->addSelect('ow.surname')
            ->addSelect('ow.name')
            ->addSelect('count(md.id) requestsCount')
            ->addSelect('DATEDIFF(:today,es.deletedAt) as days')->setParameter('today', $today)
            ->leftJoin('s.request', 'r')
            ->leftJoin('r.order', 'or')
            ->leftJoin('or.materialData', 'md')
            ->leftJoin('r.events', 'es')
            ->leftJoin('r.owner', 'ow')
            ->andWhere('s.type = :type')->setParameter('type', 'received')
            ->andWhere('s.current = :current')->setParameter('current', true)
            ->andWhere('s.instance = :instance')->setParameter('instance', $instance->getId())
            ->andWhere('es INSTANCE OF Celsius3CoreBundle:Event\SingleInstanceReceiveEvent')
            ->andWhere('ow.wrongEmail = :wrongEmail')->setParameter('wrongEmail', false)
            ->orderBy('ow.id')
            ->groupBy('ow.id')
            ->addGroupBy('days')
            ->having('days >= :minDays')->setParameter('minDays', $minDays)
            ->andHaving('days <= :maxDays')->setParameter('maxDays', $maxDays)
            ->getQuery()->getResult();
    }


    public function countUserOrdersEntreFechas(Instance $instance, BaseUser $user,$fecha_desde=null,$fecha_hasta=null)
    {
        $types = StateManager::$stateTypes;
        $qb = $this->createQueryBuilder('s')
            ->select('s.type, COUNT(s.id) as c')
            ->leftJoin('s.request', 'r')
            ->andWhere('s.current = true')
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



    public function countOrdersEntreFechas(Instance $instance = null, BaseUser $user = null, $orderType = null,$fecha_desde=null,$fecha_hasta=null)
    {


        $types = StateManager::$stateTypes;
        $qb = $this->createQueryBuilder('s')
            ->select('s.type, COUNT(s.id) as c')
            ->leftJoin('s.request', 'r')
        //    ->where('s.current = true')
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

        if (($fecha_desde!=null) and ($fecha_hasta!=null)) {
            $qb ->andWhere('s.deletedAt >= :fecha_desde')->setParameter('fecha_desde', $fecha_desde)
                ->andWhere('s.deletedAt <= :fecha_hasta')->setParameter('fecha_hasta', $fecha_hasta);
        }
    //echo $instance->getId();
     //   echo $user->getId();
       // echo $fecha_desde;
       // echo $fecha_hasta;
       // echo $qb->getQuery()->getSQL();
       // die;


        $result = array();
        foreach ($qb->getQuery()->getResult() as $type) {
            $result[$type['type']] = intval($type['c']);
        }

        foreach ($types as $state) {
            if (!array_key_exists($state, $result)) {
                $result[$state] = 0;
            }
        }

        // Se cuentan los pedidos solicitados que tienen busquedas pendientes
        // para restarlos a los pedidos solicitados
        $qb4 = $this->createQueryBuilder('s')
            ->select('s.type, COUNT(s.id) as c')
            ->leftJoin('s.request', 'r')
          //  ->where('s.current = :current')
          //  ->setParameter('current', true)
            ->andWhere('s.type = :type')
            ->setParameter('type', StateManager::STATE__REQUESTED)
            ->andWhere('s.searchPending = :pending')
            ->setParameter('pending', true)
            ->groupBy('s.type');

        if (!is_null($instance)) {
            $qb4->andWhere('s.instance = :instance')
                ->setParameter('instance', $instance->getId());
        }
        if (!is_null($user)) {
            $qb4->andWhere('(r.operator = :user)')
                ->setParameter('user', $user);
        }

        if (($fecha_desde!=null) and ($fecha_hasta!=null)) {
            $qb4 ->andWhere('s.deletedAt >= :fecha_desde')->setParameter('fecha_desde', $fecha_desde)
                ->andWhere('s.deletedAt <= :fecha_hasta')->setParameter('fecha_hasta', $fecha_hasta);
        }


        $pending = $qb4->getQuery()->getOneOrNullResult();
        if (!is_null($pending)) {
            $result[$pending['type']] -= $pending['c'];
        }

        // Se cuentan aquellos que tienen busquedas pendientes
        $qb2 = $this->createQueryBuilder('s')
            ->select('COUNT(s.id) as c')
            ->andWhere('s.instance = :instance')
         //   ->andWhere('s.current = true')
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

        if (($fecha_desde!=null) and ($fecha_hasta!=null)) {
            $qb2 ->andWhere('s.deletedAt >= :fecha_desde')->setParameter('fecha_desde', $fecha_desde)
                ->andWhere('s.deletedAt <= :fecha_hasta')->setParameter('fecha_hasta', $fecha_hasta);
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
           //     ->andWhere('s.current = true')
                ->andWhere('s.type = :type')
                ->groupBy('s.type')
                ->setParameter('type', StateManager::STATE__CREATED)
                ->setParameter('instance', $instance->getId());

            if (!is_null($orderType)) {
                $qb3 = $qb3->leftJoin('s.request', 'r')
                    ->andWhere('r.type = :orderType')
                    ->setParameter('orderType', $orderType);
            }
            if (($fecha_desde!=null) and ($fecha_hasta!=null)) {
                $qb3 ->andWhere('s.deletedAt >= :fecha_desde')->setParameter('fecha_desde', $fecha_desde)
                    ->andWhere('s.deletedAt <= :fecha_hasta')->setParameter('fecha_hasta', $fecha_hasta);
            }
            $qb3 = $qb3->getQuery()->getResult();

            if (count($qb3) > 0) {
                $result[StateManager::STATE__CREATED] = intval($qb3[0]['c']);
            }
        }

        return $result;
    }


    public function findRequestsStateCountForUser($instance, $initialYear, $finalYear,$user)
    {
        $qb = $this->createQueryBuilder('s');
        if (!is_null($instance)) {
            $qb = $qb->andWhere('s.instance = :instance')->setParameter('instance', $instance);
        }

        $qb = $qb->select('s.type stateType')
            ->addSelect('COUNT(r.id) requestsCount')
            ->addSelect('SUM(f.pages) pages')
            ->innerJoin('s.request', 'r')
            ->leftJoin('r.files', 'f')
            ->andWhere('s.type <> :stateType')->setParameter('stateType', 'annulled')
            ->groupBy('stateType');

        if ($initialYear === $finalYear) {
            $qb = $qb->addSelect('YEAR(s.deletedAt) year')
                ->addSelect('MONTH(s.deletedAt) axisValue')
                ->andWhere('YEAR(s.deletedAt) = :year')->setParameter('year', $initialYear)
                ->addGroupBy('year');
        } elseif ($initialYear < $finalYear) {
            $qb = $qb->addSelect('YEAR(s.deletedAt) axisValue')
                ->andHaving('axisValue >= :initialYear')->setParameter('initialYear', $initialYear)
                ->andHaving('axisValue <= :finalYear')->setParameter('finalYear', $finalYear);
        }

        $qb = $qb->addGroupBy('axisValue')
            ->orderBy('axisValue', 'ASC');
        return $qb->getQuery()->getResult();
    }

}
