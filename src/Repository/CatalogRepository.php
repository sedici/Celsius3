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

use Celsius3\Entity\CatalogResult;
use Celsius3\Entity\Instance;
use Doctrine\ORM\QueryBuilder;

/**
 * CatalogRepository.
 */
class CatalogRepository extends BaseRepository
{
    public function findForInstanceAndGlobal(Instance $instance, Instance $directory)
    {
        return $this->createQueryBuilder('c')
                        ->select('c, cp')
                        ->join('c.positions', 'cp')
                        ->where('c.instance = :instance_id')
                        ->orWhere('c.instance = :directory_id')
                        ->andWhere('cp.instance = :instance_id')
                        ->orderBy('cp.position', 'asc')
                        ->setParameter('instance_id', $instance->getId())
                        ->setParameter('directory_id', $directory->getId());
    }

    public function findForInstanceAndGlobalWithoutDisabled(Instance $instance, Instance $directory)
    {
        return $this->createQueryBuilder('c')
            ->select('c, cp')
            ->join('c.positions', 'cp')
            ->where('c.instance = :instance_id')
            ->orWhere('c.instance = :directory_id')
            ->andWhere('cp.instance = :instance_id')
            ->andWhere('cp.enabled = :enabled')
            ->orderBy('cp.position', 'asc')
            ->setParameter('instance_id', $instance->getId())
            ->setParameter('enabled', true)
            ->setParameter('directory_id', $directory->getId());
    }

    public function getCatalogResults($catalogs, $title)
    {
        return $this->getEntityManager()
                        ->getRepository(CatalogResult::class)
                        ->createQueryBuilder('cr')
                        ->where('cr.title = :title')
                        ->andWhere('cr.catalog IN (:catalog_ids)')
                        ->setParameter('title', $title)
                        ->setParameter('catalog_ids', $catalogs)
                        ->getQuery()
                        ->getResult();
    }

    public function getDisabledCatalogsCount(Instance $instance, Instance $directory)
    {
        return $this->createQueryBuilder('c')
                ->select('COUNT(DISTINCT c.id)')
                ->join('c.positions', 'cp')
                ->where('c.instance = :instance_id')
                ->orWhere('c.instance = :directory_id')
                ->orderBy('cp.position', 'asc')
                ->setParameter('instance_id', $instance->getId())
                ->setParameter('directory_id', $directory->getId())
                ->andWhere('cp.enabled = :enabled')
                ->setParameter('enabled', false)
                ->getQuery()
                ->getSingleScalarResult();
    }

    public function addFindByCity($city, QueryBuilder $query, Instance $instance = null)
    {
        $query = $query->andWhere('ci.city = :city_id')
                        ->setParameter('city_id', $city->getId());
        return $query;
    }

    public function addFindByCountry($country, QueryBuilder $query, Instance $instance = null)
    {
        $alias = $query->getRootAliases()[0];
        $query = $query->join($alias.'.institution', 'ci')
                        ->andWhere('ci.country = :country_id')
                        ->setParameter('country_id', $country->getId());
        return $query;
    }
}
