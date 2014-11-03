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

class CityRepository extends EntityRepository
{

    public function findForInstanceAndGlobal(Instance $instance, Instance $directory, $country_id = null)
    {
        $qb = $this->createQueryBuilder('c')
                ->where('c.instance_id = :instance_id')
                ->orWhere('c.instance_id = :directory_id')
                ->setParameter('instance_id', $instance->getId())
                ->setParameter('directory_id', $directory->getId());

        if (!is_null($country_id)) {
            $qb = $qb->andWhere('c.country_id = :country_id')
                    ->setParameter('country_id', $country_id);
        }

        return $qb->getQuery()->getResult();
    }
}