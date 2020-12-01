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

use Celsius3\CoreBundle\Entity\Country;
use Celsius3\CoreBundle\Entity\Instance;

/**
 * CityRepository.
 */
class CityRepository extends BaseRepository
{
    public function findForInstanceAndGlobal(Instance $instance, Instance $directory, $country_id = null)
    {
        $qb = $this->createQueryBuilder('e')
                ->select('e')
                ->addSelect('c')
                ->innerJoin('e.country', 'c')
                ->where('e.instance = :instance_id')
                ->orWhere('e.instance = :directory_id')
                ->orderBy('e.name', 'asc')
                ->setParameter('instance_id', $instance->getId())
                ->setParameter('directory_id', $directory->getId());

        if (!is_null($country_id)) {
            $qb = $qb->andWhere('e.country = :country_id')
                    ->setParameter('country_id', $country_id);
        }

        return $qb;
    }

    public function findForCountryQB(Country $country = null)
    {
        $qb = $this->createQueryBuilder('c');

        if ($country instanceof Country) {
            $qb->where('c.country = :country_id')
                    ->setParameter('country_id', $country->getId());
        } else {
            $qb->where('c.country IS NULL');
        }

        return $qb->orderBy('c.name', 'asc');
    }

    public function findForCountry($country_id)
    {
        return $this->createQueryBuilder('c')
                    ->where('c.country = :cid')
                    ->setParameter('cid', $country_id)
                    ->orderBy('c.name', 'ASC')
                    ->getQuery()->getResult();
    }
}
