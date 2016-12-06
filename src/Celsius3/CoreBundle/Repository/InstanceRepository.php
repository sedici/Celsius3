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

use Celsius3\CoreBundle\Manager\InstanceManager;

/**
 * InstanceRepository.
 */
class InstanceRepository extends BaseRepository
{
    public function findAllExceptDirectory()
    {
        return $this->createQueryBuilder('i')
                    ->where('i.url <> :url')
                    ->andWhere('i.invisible <> :invisible')
                    ->setParameter('url', InstanceManager::INSTANCE__DIRECTORY)
                    ->setParameter('invisible', true);
    }

    public function findAllEnabledAndVisible()
    {
        return $this->createQueryBuilder('i')
                    ->select('o, c, i')
                    ->innerJoin('i.ownerInstitutions', 'o')
                    ->innerJoin('o.country', 'c')
                    ->where('i.enabled = :enabled')
                    ->andWhere('i.invisible = :invisible')
                    ->setParameter('enabled', true)
                    ->setParameter('invisible', false)
                    ->getQuery()->getResult();
    }

    public function findAllInstancesExceptByUrl($url)
    {
        return $this->createQueryBuilder()
                    ->field('url')
                    ->notEqual($url)
                    ->getQuery()
                    ->execute();
    }

    public function findAllAndInvisibleExceptDirectory()
    {
        return $this->createQueryBuilder('i')
                        ->where('i.url <> :url')
                        ->setParameter('url', InstanceManager::INSTANCE__DIRECTORY);
    }
}
