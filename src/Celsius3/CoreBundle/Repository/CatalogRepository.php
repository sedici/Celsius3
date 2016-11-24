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

use Doctrine\ORM\EntityRepository;
use Celsius3\CoreBundle\Entity\Instance;

class CatalogRepository extends EntityRepository
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

    public function getCatalogResults($catalogs, $title)
    {
        return $this->getEntityManager()
                        ->getRepository('Celsius3CoreBundle:CatalogResult')
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
}
