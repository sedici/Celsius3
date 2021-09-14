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

namespace Celsius3\Repository;

use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Manager\InstanceManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class InstanceRepository extends ServiceEntityRepository implements InstanceRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Instance::class);
    }

    public function findBaseDoUnionEntities($main, $ids)
    {
        return $this->createQueryBuilder('e')
            ->where('e.id IN (:ids)')
            ->andWhere('e.id <> :id')
            ->setParameter('ids', $ids)
            ->setParameter('id', $main->getId())
            ->getQuery()->getResult();
    }

    public function findOneForInstance(Instance $instance, $id)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.instance = :instance_id')
            ->andWhere('e.id = :id')
            ->setParameter('instance_id', $instance->getId())
            ->setParameter('id', $id)
            ->getQuery()->getOneOrNullResult();
    }

    public function union($field, $main_id, $elements)
    {
        return $this->createQueryBuilder('e')
            ->update()
            ->set('e.' . $field, ':main_id')
            ->where('e.' . $field . ' IN (:ids)')
            ->setParameter('ids', $elements)
            ->setParameter('main_id', $main_id)
            ->getQuery()->getResult();
    }

    public function deleteUnitedEntities(array $elements)
    {
        return $this->createQueryBuilder('e')
            ->delete()
            ->where('e.id IN (:ids)')
            ->setParameter('ids', $elements)
            ->getQuery()->getResult();
    }

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

    public function findAllDomains()
    {
        return $this->createQueryBuilder('i')
            ->select('i.host')
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

    public function findInstancesOrderedByDistance($latitude, $longitude, $limit)
    {
        return $this->createQueryBuilder('i')
            ->addSelect('i')
            ->addSelect(
                '( 3959 * ACOS( COS( RADIANS( :lat ) ) * COS( RADIANS( i.latitud ) ) * COS( RADIANS( i.longitud ) - RADIANS( :lon ) ) + SIN( RADIANS( :lat ) ) * SIN( RADIANS( i.latitud ) ) ) ) AS distance'
            )
            ->setParameters(
                [
                    'lat' => $latitude,
                    'lon' => $longitude,
                ]
            )
            ->addOrderBy('distance', 'ASC')
            /*->setMaxResults($limit)*/
            ->getQuery()
            ->getResult();
    }

    public function save(Instance $instance): void
    {
        $this->getEntityManager()->persist($instance);
        $this->getEntityManager()->flush();
    }
}
