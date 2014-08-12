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

class MailTemplateRepository extends DocumentRepository
{

    public function findGlobalAndForInstance(Instance $instance, Instance $directory)
    {
        $custom = array_map(function ($elem) {
            return $elem['code'];
        }, $this->createQueryBuilder()
                        ->hydrate(false)
                        ->select('code')
                        ->field('instance.id')->equals($instance->getId())
                        ->field('enabled')->equals(true)
                        ->getQuery()
                        ->execute()
                        ->toArray());

        $qb = $this->createQueryBuilder();

        return $qb->addOr($qb->expr()->field('instance.id')->equals($directory->getId())
                                ->field('code')->notIn($custom)
                                ->field('enabled')->equals(true))
                        ->addOr($qb->expr()->field('instance.id')->equals($instance->getId()));
    }
}