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

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * AnalyticsRepository
 */
class AnalyticsRepository extends DocumentRepository
{

    public function getyearUsersCounts()
    {
        return $this->createQueryBuilder()->field('type')->equals('user')
                        ->sort('year')->getQuery()->execute();
    }

    public function getMonthUsersCountsFor($year)
    {
        return $this->createQueryBuilder()
                        ->field('type')->equals('user')
                        ->field('year')->equals($year)
                        ->getQuery()->execute();
    }

    public function getYears()
    {
        return $this->createQueryBuilder()
                        ->field('type')->equals('user')
                        ->select('year')
                        ->sort('year')->getQuery()->execute();
    }

    public function getUsersCountDataForYearsInterval($initialYear, $finalYear)
    {
        return $this->createQueryBuilder()
                        //->field('instance.id')->equals($instance)
                        ->field('type')->equals('user')
                        ->field('year')->gte(intval($initialYear))->lte(intval($finalYear))
                        ->sort('year')
                        ->getQuery()->execute();
    }

    public function getUsersCountDataForYear($year)
    {
        return $this->createQueryBuilder()
                        //->field('instance.id')->equals($instance)
                        ->field('type')->equals('user')
                        ->field('year')->equals(intval($year))
                        ->getQuery()->execute()->getSingleResult();
    }

}
