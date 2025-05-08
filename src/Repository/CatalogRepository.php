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

use Celsius3\Entity\CatalogPosition;
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
        return $this->createQueryBuilder('e')
            ->where('e.instance = :instance_id')
            ->orWhere('e.instance = :directory_id')
            ->setParameter('instance_id', $instance->getId())
            ->setParameter('directory_id', $directory->getId())
            ->orderBy('e.name', 'asc');
    }

    public function findForInstanceAndGlobalWithoutDisabled(Instance $instance, Instance $directory)
    {
        return $this->createQueryBuilder('e')
            ->innerJoin(CatalogPosition::class, 'cp', 'WITH', 'e.instance = cp.instance AND e.id = cp.catalog')
            ->where('(cp.instance = :instance_id OR cp.instance = :directory_id)')
            ->andWhere('cp.enabled = :enabled')
            ->setParameter('instance_id', $instance->getId())
            ->setParameter('directory_id', $directory->getId())
            ->setParameter('enabled', true)
            ->orderBy('e.name', 'ASC');
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
        return $this->createQueryBuilder('e')
            ->innerJoin(CatalogPosition::class, 'cp', 'WITH', 'e.instance = cp.instance AND e.id = cp.catalog')
            ->select('COUNT(DISTINCT e.id)')
            ->where('e.instance = :instance_id')
            ->orWhere('e.instance = :directory_id')
            ->andWhere('cp.enabled = :enabled')
            ->setParameter('instance_id', $instance->getId())
            ->setParameter('directory_id', $directory->getId())
            ->setParameter('enabled', false)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function addFindByCity(
        $data,
        QueryBuilder $query,
        Instance $instance = null
    ): QueryBuilder {
        $alias = $query->getRootAliases()[0];

        $query = $query->join('i.city', 'cy')
            ->andWhere('cy.id = :city')
            ->setParameter('city', $data);

        if (!is_null($instance)) {
            $query = $query->andWhere($alias . '.instance = :instance_id')
                ->setParameter('instance_id', $instance->getId());
        }

        return $query;
    }

    public function addFindByCountry(
        $data,
        QueryBuilder $query,
        Instance $instance = null
    ): QueryBuilder {
        $alias = $query->getRootAliases()[0];

        $query = $query->join($alias . '.institution', 'i')
            ->join('i.country', 'c')
            ->andWhere('c.id = :country')
            ->setParameter('country', $data);

        if (!is_null($instance)) {
            $query = $query->andWhere($alias . '.instance = :instance_id')
                ->setParameter('instance_id', $instance->getId());
        }

        return $query;
    }
}
