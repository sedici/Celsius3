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

use Celsius3\Entity\Contact;
use Celsius3\Entity\Instance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * ContactRepository.
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function findByInstance(Instance $instance, $id)
    {
        return $this->createQueryBuilder('e')
            ->where('e.owningInstance = :owning')->setParameter('owning', $instance->getId())
            ->andWhere('e.id = :id')->setParameter('id', $id)
            ->getQuery()->getSingleResult();
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

    public function save($entity): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($entity);
        $entityManager->flush();
    }

    public function remove($entity): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->remove($entity);
        $entityManager->flush();
    }
}