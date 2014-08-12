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

class CatalogRepository extends DocumentRepository
{

    public function findForInstanceAndGlobal(Instance $instance, Instance $directory)
    {
        $qb = $this->getDocumentManager()->getRepository('Celsius3CoreBundle:CatalogPosition')
                ->createQueryBuilder()
                ->hydrate(false)
                ->select('catalog');

        $positions = array_map(function ($item) {
            return $item['catalog']['$id'];
        }, $qb->addOr($qb->expr()->field('instance.id')->equals($directory->getId()))
                        ->addOr($qb->expr()->field('instance.id')->equals($instance->getId()))
                        ->sort('position', 'asc')
                        ->getQuery()
                        ->execute()
                        ->toArray());

        $qb2 = $this->createQueryBuilder();

        foreach ($positions as $position) {
            $qb2 = $qb2->addOr($qb2->expr()->field('id')->equals($position));
        }

        return $qb2;
    }

}