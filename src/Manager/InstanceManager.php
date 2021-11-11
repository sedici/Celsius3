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

use Celsius3\CoreBundle\Entity\Instance;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class InstanceManager
{
    public const INSTANCE__DIRECTORY = 'directory';
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getDirectory()
    {
        return $this->em->getRepository(Instance::class)
                        ->findOneBy(array('url' => self::INSTANCE__DIRECTORY));
    }

    public function findInstance($latitude, $longitude, $limit = 10)
    {
        $temRes = $this->em->getRepository(Instance::class)
            ->findInstancesOrderedByDistance($latitude, $longitude, $limit);

        $res = [];
        foreach ($temRes as $tem) {
            $res[] = $tem[0];
        }

        return $res;
    }


}
