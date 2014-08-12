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
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Document\Hive;

class InstitutionRepository extends DocumentRepository
{

    public function findForInstanceAndGlobal(Instance $instance, Instance $directory, Hive $hive, $country_id, $city_id = null, $filter = null)
    {
        $qb = $this->getDocumentManager()->getRepository('Celsius3CoreBundle:Institution')
                ->createQueryBuilder();

        $qb = $qb->addOr($qb->expr()->field('instance.id')->equals($directory->getId()))
                        ->addOr($qb->expr()->field('instance.id')->equals($instance->getId()))
                        ->field('country.id')->equals($country_id)
                        ->field('parent.id')->equals(null);

        if (!is_null($city_id)) {
            $qb = $qb->field('city.id')->equals($city_id);
        }

        if (!is_null($filter)) {
            if ($filter === 'hive') {
                $qb = $qb->field('hive.id')->equals($hive->getId());
            } elseif ($filter === 'celsius3') {
                $qb = $qb->field('celsiusInstance.id')->notEqual(null);
            }
        }

        return $qb->getQuery()
                        ->execute()
                        ->toArray();
    }

}