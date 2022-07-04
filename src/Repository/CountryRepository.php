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

namespace Celsius3\Repository;

use Celsius3\Entity\Instance;

/**
 * CountryRepository.
 */
class CountryRepository extends BaseRepository
{
    public function findForInstanceAndGlobal(Instance $instance, Instance $directory)
    {
        return $this->createQueryBuilder('e')
                        ->where('e.instance = :instance_id')
                        ->orWhere('e.instance = :directory_id')
                        ->orderBy('e.name', 'asc')
                        ->setParameter('instance_id', $instance->getId())
                        ->setParameter('directory_id', $directory->getId());
    }

    public function getAllOrderedByNameQB()
    {
        return $this->createQueryBuilder('c')
                    ->orderBy('c.name', 'asc');
    }
}
