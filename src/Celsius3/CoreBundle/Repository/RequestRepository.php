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
use Doctrine\ORM\QueryBuilder;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Manager\UserManager;
use Doctrine\ORM\Query\Expr\Join;

/**
 * RequestRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RequestRepository extends EntityRepository
{

    public function countByMonthAndYear()
    {

        $qb = $this->createQueryBuilder();
        $requests = $qb
                        ->map('function() { emit( , 1) }')
                        ->reduce('function(k, vals) {
                var sum = 0;
                for (var i in vals) {
                    sum += vals[i];
                }
                return sum;
            }')->getQuery()->execute()->toArray();

        $string = '';
        foreach ($requests as $r) {
            $string .= $r['_id'] . '-' . $r['value'] . '<br>';
        }
        return $string;
    }

    public function countActiveUsersFor($instance, $type, $initialYear, $finalYear)
    {
        $qb = $this->createQueryBuilder('request');

        if ($initialYear === $finalYear) {
            $qb = $qb->select('MONTH(request.createdAt) axisValue')
                            ->andWhere('YEAR(request.createdAt) = :year')->setParameter('year', $initialYear);
        } else if ($initialYear < $finalYear) {
            $qb = $qb->select('YEAR(request.createdAt) axisValue')
                            ->andHaving('axisValue >= :initialYear')->setParameter('initialYear', $initialYear)
                            ->andHaving('axisValue <= :finalYear')->setParameter('finalYear', $finalYear);
        }

        $qb = $qb->addSelect('COUNT(DISTINCT request.owner) activeUsers')
                ->andWhere('request.instance = :instance')->setParameter('instance', $instance)
                ->andWhere('request.type = :type')->setParameter('type', $type)
                ->groupBy('axisValue')
                ->orderBy('axisValue', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function getYears($instance)
    {
        return $this->createQueryBuilder('request')
                        ->select('YEAR(request.createdAt) year')
                        ->where('request.instance = :instance')->setParameter('instance', $instance)
                        ->groupBy('year')
                        ->orderBy('year')
                        ->getQuery()
                        ->getResult();
    }

    public function findRequestsDestinyDistributionForInterval($instance, $type, $initialYear, $finalYear)
    {
        $this->createQueryBuilder('p')
                ->addSelect('c.id countryId')
                ->addSelect('c.name countryName')
                ->addSelect('YEAR(s.createdAt) year')
                ->addSelect('s.type stateType')
                ->addSelect('COUNT(s.request) requestsCount')
                ->innerJoin();
    }

    public function findRequestsNumberByPublicationYearFor($instance, $type, $initialYear, $finalYear)
    {
        $qb = $this->createQueryBuilder('r');

        $qb = $qb->addSelect('md.year materialDataYear')
                ->addSelect('COUNT(md.id) materialDataCount')
                ->innerJoin('r.order', 'o')
                ->innerJoin('o.materialData', 'md')
                ->andWhere('r.instance = :instance')->setParameter('instance', $instance)
                ->andWhere('r.type = :type')->setParameter('type', $type)
                ->groupBy('materialDataYear')
                ->orderBy('materialDataYear', 'ASC');

        if ($initialYear === $finalYear) {
            $qb = $qb->andWhere('YEAR(r.createdAt) = :year')->setParameter('year', $initialYear);
        } else if ($initialYear < $finalYear) {
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
                )
                AND r.instance = :instance
                AND r.type = :type ";

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
            $dql .= "AND YEAR(r.createdAt) = :year ";
        }

        $dql .= "GROUP BY cYear,delay ";

        if ($initialYear < $finalYear) {
            $dql .= "HAVING cYear >= :rAyear
                     AND cYear <= :rByear ";
        }

        $dql .= "ORDER BY cYear,delay";

        $query = $this->getEntityManager()->createQuery($dql);
        $query = $query->setParameter('instance', $instance)
                ->setParameter('type', $type);

        if ($initialYear === $finalYear) {
            $query = $query->setParameter('year', $initialYear);
        }

        if ($initialYear < $finalYear) {
            $query = $query->setParameter('rAyear', $initialYear)
                    ->setParameter('rByear', $finalYear);
        }

        return $query->getResult();
    }

}
