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

namespace Celsius3\CoreBundle\Manager;

use Doctrine\ORM\EntityManager;

class InstanceManager
{
    const INSTANCE__DIRECTORY = 'directory';
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getDirectory()
    {
        return $this->em->getRepository('Celsius3CoreBundle:Instance')
                        ->findOneBy(array('url' => self::INSTANCE__DIRECTORY));
    }

    public function findInstance($latitude, $longitude, $limit = 5)
    {
        $temRes =  $this->em->createQueryBuilder()
            ->addSelect('i')
            ->addSelect('( 3959 * ACOS( COS( RADIANS( :lat ) ) * COS( RADIANS( i.lat ) ) * COS( RADIANS( i.long ) - RADIANS( :lon ) ) + SIN( RADIANS( :lat ) ) * SIN( RADIANS( i.lat ) ) ) ) AS distance')
            ->from('Celsius3CoreBundle:Instance', 'i')
            ->setParameters(array(
                'lat' => $latitude,
                'lon' => $longitude,
            ))
            ->addOrderBy('distance', 'ASC')
            ->setMaxResults($limit)->getQuery()->getResult();


        $res = [];
        foreach($temRes as $tem) {
            $res[] = $tem[0];
        }

        return $res;
    }


}
