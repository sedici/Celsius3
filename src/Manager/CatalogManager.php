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

namespace Celsius3\Manager;

use Celsius3\Entity\Catalog;
use Celsius3\Entity\Instance;
use Doctrine\ORM\EntityManager;
use Celsius3\Helper\InstanceHelper;


class CatalogManager
{
    public const CATALOG__NON_SEARCHED = 'non_searched';
    public const CATALOG__FOUND = 'found';
    public const CATALOG__PARTIALLY_FOUND = 'partially_found';
    public const CATALOG__NOT_FOUND = 'not_found';

    protected $repository;

    public function __construct(
        protected EntityManager $entityManager,
        protected InstanceHelper $instanceHelper
    ) {
        $this->repository = $this->entityManager->getRepository(Catalog::class);
    }


    public static function getResults(): array
    {
        return [
            self::CATALOG__FOUND,
            self::CATALOG__PARTIALLY_FOUND,
            self::CATALOG__NOT_FOUND,
            self::CATALOG__NON_SEARCHED
        ];
    }


    public function getCatalogs(?Instance $instance = null): array
    {
        return $this->repository->findBy(
            [ 'instance' => $instance->getId() ]
        );
    }
}
