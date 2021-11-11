<?php

declare(strict_types=1);

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

namespace Celsius3\Filter;

use Celsius3\CoreBundle\Entity\Catalog;
use Doctrine\ORM\EntityManagerInterface;

class CatalogFilter implements EntityFilterInterface
{
    private $entityManager;
    private $specialFields = [
        'city' => 'addFindByCity',
        'country' => 'addFindByCountry'
    ];

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function applyCustomFilter($field_name, $data, $query, $instance)
    {
        $function = $this->specialFields[$field_name];

        return $this->entityManager->getRepository(Catalog::class)
            ->$function(
                $data,
                $query,
                $instance
            );
    }

    public function hasCustomFilter($field_name)
    {
        return array_key_exists($field_name, $this->specialFields);
    }
}
