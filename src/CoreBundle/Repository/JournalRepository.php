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

use Celsius3\CoreBundle\Entity\Instance;

/**
 * JournalRepository.
 */
class JournalRepository extends BaseRepository
{
    public function findForInstanceAndGlobal(Instance $instance, Instance $directory)
    {
        return $this->createQueryBuilder('e')
                        ->where('e.instance = :instance_id')
                        ->orWhere('e.instance = :directory_id')
                        ->setParameter('instance_id', $instance->getId())
                        ->setParameter('directory_id', $directory->getId());
    }

    public function findByTerm($term, Instance $instance = null)
    {
        $qb = $this->createQueryBuilder('j');

        $qb = $qb->orWhere($qb->expr()->like('j.name', $qb->expr()->literal('%'.$term.'%')))
                ->orWhere($qb->expr()->like('j.abbreviation', $qb->expr()->literal('%'.$term.'%')))
                ->orWhere($qb->expr()->like('j.responsible', $qb->expr()->literal('%'.$term.'%')))
                ->orWhere($qb->expr()->like('j.ISSN', $qb->expr()->literal('%'.$term.'%')))
                ->orWhere($qb->expr()->like('j.ISSNE', $qb->expr()->literal('%'.$term.'%')));

        $directory = $this->getEntityManager()->getRepository('Celsius3CoreBundle:Instance')
                ->findOneBy(array(
            'url' => \Celsius3\Manager\InstanceManager::INSTANCE__DIRECTORY,
        ));

        if (!is_null($instance)) {
            $qb = $qb->andWhere('j.instance = :instance_id OR j.instance = :directory_id')
                    ->setParameter('instance_id', $instance->getId())
                    ->setParameter('directory_id', $directory->getId());
        }

        return $qb->getQuery();
    }

    public function findOneForInstanceOrGlobal(Instance $instance, Instance $directory, $id)
    {
        return $this->createQueryBuilder('e')
                    ->where('e.instance = :instance_id')
                    ->orWhere('e.instance = :directory_id')
                    ->andWhere('e.id = :id')
                    ->setParameter('instance_id', $instance)
                    ->setParameter('directory_id', $directory)
                    ->setParameter('id', $id)
                    ->getQuery()->getOneOrNullResult();
    }

    public function findQuery(Instance $instance, $id, $isAdmin = false)
    {
        $qb = $this->createQueryBuilder('e');

        if (!$isAdmin) {
            $qb = $qb->andWhere('e.instance = :instance_id')
            ->setParameter('instance_id', $instance->getId());
        }

        return $qb->andWhere('e.id = :id')
                ->setParameter('id', $id)
                ->getQuery()->getOneOrNullResult();
    }
}
