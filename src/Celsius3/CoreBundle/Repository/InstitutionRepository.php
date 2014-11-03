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
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Document\Hive;

class InstitutionRepository extends EntityRepository
{

    public function findForInstanceAndGlobal(Instance $instance, Instance $directory, Hive $hive, $country_id, $city_id = null, $filter = null)
    {
        $qb = $this->createQueryBuilder('i')
                ->where('c.instance_id = :instance_id')
                ->orWhere('c.instance_id = :directory_id')
                ->andWhere('i.country_id = :country_id')
                ->andWhere('i.parent_id IS NULL')
                ->setParameter('instance_id', $instance->getId())
                ->setParameter('directory_id', $directory->getId())
                ->setParameter('country_id', $country_id);

        if (!is_null($city_id)) {
            $qb = $qb->andWhere('i.city_id = :city_id')
                    ->setParameter('city_id', $city_id);
        }

        if (!is_null($filter)) {
            if ($filter === 'hive') {
                $qb = $qb->andWhere('i.hive_id')
                        ->setParameter('hive_id', $hive->getId());
            } elseif ($filter === 'celsius3') {
                $qb = $qb->andWhere('i.celsius_instance_id IS NOT NULL');
            }
        }

        return $qb->getQuery()->getResult();
    }
}