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

    public function countOrders(Instance $instance = null, BaseUser $user = null)
    {
        $types = StateManager::$stateTypes;
        $qb = $this->createQueryBuilder('s')
                ->select('s.type, COUNT(s.id) as c')
                ->where('s.isCurrent = true')
                ->andWhere('s.type IN (:types)')
                ->groupBy('s.type')
                ->setParameter('types', $types);

        if (!is_null($instance)) {
            $qb = $qb->andWhere('s.instance = :instance_id')
                    ->setParameter('instance_id', $instance->getId());
        }

        if (!is_null($user)) {
            $qb = $qb->andWhere('(s.operator = :user_id OR s.operator IS NULL)')
                    ->setParameter('user_id', $user->getId());
        }

        $result = array();
        foreach ($qb->getQuery()->getResult() as $type) {
            $result[$type['type']] = intval($type['c']);
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

    public function findRequestsStateCountForInterval($instance, $initialYear, $finalYear)
    {
        $query = $this->createQueryBuilder('x')
                        ->select('x')
                        ->andWhere('x.type = :type')->setParameter('type', 'annulled')
                        ->andWhere('x.instance = :instance')->setParameter('instance', $instance)
                        ->innerJoin('x.request', 'r')
                        ->getQuery()->getResult();

        $qb = $this->createQueryBuilder('s');
        return $qb->addSelect('YEAR(s.createdAt) year')
                        ->addSelect('s.type stateType')
                        ->addSelect('COUNT(s.request) requestsCount')
                        ->addSelect('( SUM(md.endPage) - SUM(md.startPage) ) totalPages')
                        ->innerJoin('s.request', 'r')
                        ->innerJoin('r.order','o')
                        ->innerJoin('o.materialData','md', Join::ON, $qb->expr()->eq('md.orderId', 'o.id'))
                        ->andWhere('s.instance = :instance')->setParameter('instance', $instance)
                        ->andWhere($qb->expr()->notIn('s.request', $query))
                        ->andHaving('year >= :initialYear')->setParameter('initialYear', $initialYear)
                        ->andHaving('year <= :finalYear')->setParameter('finalYear', $finalYear)
                        ->groupBy('year')
                        ->addGroupBy('s.type')
                        ->orderBy('year', 'ASC')
                        ->getQuery()->getResult();

//        --CONSULTA--
//        
//        SELECT YEAR(s.createdAt),MONTH(s.createdAt),s.type,count(s.request_id)
//        FROM celsius3.state s
//        WHERE s.request_id NOT IN ( SELECT s.request_id FROM state s WHERE s.type = 'annulled' )
//        GROUP BY YEAR(s.createdAt),MONTH(s.createdAt),s.type
//        ORDER BY YEAR(s.createdAt) ASC, MONTH(s.createdAt) ASC,s.type ASC
    }

    public function findRequestsStateCountForYear($instance, $year)
    {
        $query = $this->createQueryBuilder('x')
                        ->select()
                        ->andWhere('x.type = :type')
                        ->setParameter('type', 'annulled')
                        ->andWhere('x.instance = :instance')
                        ->setParameter('instance', $instance)
                        ->innerJoin('x.request', 'r')
                        ->getQuery()->getResult();

        $qb = $this->createQueryBuilder('s');
        return $qb->addSelect('MONTH(s.createdAt) year')
                        ->addSelect('s.type stateType')
                        ->addSelect('COUNT(s.request) requestsCount')
                        ->innerJoin('s.request', 'r')
                        ->innerJoin('r.order','o')
                        ->innerJoin('o.materialData','md')
                        ->andWhere('s.instance = :instance')->setParameter('instance', $instance)
                        ->andWhere('YEAR(s.createdAt) = :year')->setParameter('year', $year)
                        ->andWhere($qb->expr()->notIn('s.request', $query))
                        ->addGroupBy('year')
                        ->addGroupBy('s.type')
                        ->addOrderBy('year', 'ASC')
                        ->getQuery()->getResult();
    }

    public function getYears($instance)
    {
        $query = $this->createQueryBuilder('x')
                        ->select()
                        ->andWhere('x.type = :type')
                        ->setParameter('type', 'annulled')
                        ->andWhere('x.instance = :instance')
                        ->setParameter('instance', $instance)
                        ->innerJoin('x.request', 'r')
                        ->getQuery()->getResult();

        $qb = $this->createQueryBuilder('s');
        return $qb->addSelect('YEAR(s.createdAt) year')
                        ->andWhere('s.instance = :instance')->setParameter('instance', $instance)
                        ->andWhere($qb->expr()->notIn('s.request', $query))
                        ->groupBy('year')
                        ->orderBy('year', 'ASC')
                        ->getQuery()->getResult();
    }
    
    public function findRequestsDestinyDistributionForInterval($instance,$type,$initialYear,$finalYear){
        $query = $this->createQueryBuilder('x')
            ->select('x')
            ->andWhere('x.type = :type')->setParameter('type', 'annulled')
            ->andWhere('x.instance = :instance')->setParameter('instance', $instance)
            ->innerJoin('x.request', 'r')
            ->getQuery()->getResult();

        $qb = $this->createQueryBuilder('s');
        
        return $qb->addSelect('c.id countryId')
            ->addSelect('c.name countryName')
            ->addSelect('YEAR(s.createdAt) year')
            ->addSelect('s.type stateType')
            ->addSelect('COUNT(s.request) requestsCount')
            ->innerJoin('s.request', 'r',Join::ON, $qb->expr()->eq('IFNULL(r.state,"")', 's'))
            ->innerJoin('r.events','e',Join::ON, $qb->expr()->andX(
                $qb->expr()->eq('IFNULL(e.request,"")', 'r'),
                $qb->expr()->eq('IFNULL(e.type,"")', '"sirequest"')
            ))
            ->innerJoin('IFNULL(e.provider,"")','p')
            ->innerJoin('p.country','c')
            ->andWhere('s.instance = :instance')->setParameter('instance', $instance)
            ->andWhere('r.type = :type')->setParameter('type', $type)
            ->andWhere($qb->expr()->notIn('s.request', $query))
            ->andHaving('year >= :initialYear')->setParameter('initialYear', $initialYear)
            ->andHaving('year <= :finalYear')->setParameter('finalYear', $finalYear)
            ->groupBy('countryId')
            ->addGroupBy('stateType')
            ->orderBy('requestsCount', 'ASC')
            ->getQuery()->getResult();
    }
}
