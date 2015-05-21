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

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Manager\StateManager;

/**
 * OrderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrderRepository extends EntityRepository
{

    public function findByTerm($term, Instance $instance = null, $in = array(), $limit = null)
    {
        $qb = $this->createQueryBuilder('o')
                ->join('o.requests', 'r');

        if (count($in) > 0) {
            $secondary = array();
            foreach ($in as $repository => $term) {
                $secondary = array_map(function (BaseUser $user) {
                    return $user->getId();
                }, $this->getEntityManager()
                                ->getRepository('Celsius3CoreBundle:' . $repository)
                                ->findByTerm($term, $instance)
                                ->getResult());
                if ($repository === 'BaseUser' && count($secondary) > 0) {
                    $qb->andWhere($qb->expr()->in('r.owner', $secondary));
                }
            }
        } else {
            $qb = $qb->join('o.materialData', 'md')
                    ->orWhere($qb->expr()->like('o.code', $qb->expr()->literal('%' . $term . '%')))
                    ->orWhere($qb->expr()->like('md.title', $qb->expr()->literal('%' . $term . '%')))
                    ->orWhere($qb->expr()->like('md.authors', $qb->expr()->literal('%' . $term . '%')))
                    ->orWhere($qb->expr()->like('md.year', $qb->expr()->literal('%' . $term . '%')));
        }

        if (!is_null($instance)) {
            $qb = $qb->andWhere('r.instance = :instance')
                    ->setParameter('instance', $instance);
        }

        if (!is_null($limit)) {
            $qb = $qb->setMaxResults($limit);
        }

        return $qb->getQuery();
    }

    public function findForInstance(Instance $instance, BaseUser $user = null, $state = null, BaseUser $owner = null, $orderType = null)
    {
        $qb = $this->createQueryBuilder('o')
                ->join('o.requests', 'r')
                ->join('r.states', 's')
                ->join('o.materialData', 'm')
                ->andWhere('s.isCurrent = true')
                ->andWhere('r.instance = :instance')
                ->setParameter('instance', $instance);

        if (is_array($state) && count($state) > 0) {
            if (in_array(StateManager::STATE__SEARCHED, $state)) {
                $qb = $qb->andWhere('(s.type IN (:state_types) OR (s.type = :requested AND s.searchPending = true))')
                        ->setParameter('state_types', $state)
                        ->setParameter('requested', StateManager::STATE__REQUESTED);
            } else {
                $qb = $qb->andWhere('s.type IN (:state_types)')
                        ->setParameter('state_types', $state);
            }
        } elseif (!is_null($state)) {
            if (StateManager::STATE__SEARCHED === $state) {
                $qb = $qb->andWhere('(s.type = :state_type OR (s.type = :requested AND s.searchPending = true))')
                        ->setParameter('state_type', $state)
                        ->setParameter('requested', StateManager::STATE__REQUESTED);
            } else {
                $qb = $qb->andWhere('s.type = :state_type')
                        ->setParameter('state_type', $state);
            }
        }

        if ((!is_null($orderType) && !($orderType === 'allTypes'))) {
            $qb = $qb->andWhere('r.type = :order_type')
                    ->setParameter('order_type', $orderType);
        }

        if (!is_null($user)) {
            if (!(is_array($state) && in_array(StateManager::STATE__CREATED, $state)) && !(!is_null($state) && $state === StateManager::STATE__CREATED)) {
                $qb = $qb->andWhere('(r.operator = :user)')
                        ->setParameter('user', $user);
            }
        }

        if (!is_null($owner)) {
            $qb = $qb->andWhere('r.owner = :owner')
                    ->setParameter('owner', $owner);
        }

        return $qb;
    }

    public function findOneForInstance($id, Instance $instance)
    {
        return $this->getEntityManager()
                        ->getRepository('Celsius3CoreBundle:Order')
                        ->createQueryBuilder('o')
                        ->join('o.requests', 'r')
                        ->where('o.id = :id')
                        ->andWhere('r.instance = :instance_id')
                        ->setParameter('id', $id)
                        ->setParameter('instance_id', $instance->getId());
    }

    public function findByStateType($type, $startDate, BaseUser $user = null, Instance $instance = null)
    {
        $qb = $this->getEntityManager()
                ->getRepository('Celsius3CoreBundle:Order')
                ->createQueryBuilder('o');

        $qb->select('o.code')
                ->addSelect('ow.id owner_id')
                ->addSelect('ow.name owner_name')
                ->addSelect('ow.surname owner_surname')
                ->addSelect('ow.email owner_email')
                ->addSelect('md.startPage start_page')
                ->addSelect('md.endPage end_page')
                ->join('o.requests', 'r')
                ->join('o.materialData', 'md')
                ->join('o.originalRequest', 'or')
                ->join('or.owner', 'ow')
                ->join('r.states', 's')
                ->where('s.type = :type')
                ->setParameter('type', $type);


        if (!is_null($startDate)) {
            $qb->andWhere('s.createdAt >= :date')
                    ->setParameter('date', $startDate);
        } else {
            $qb->setMaxResults(10)
                    ->orderBy('r.createdAt', 'DESC');
        }

        if (!is_null($instance)) {
            $qb->andWhere('r.instance = :instance')
                    ->setParameter('instance', $instance);
        }

        if (!is_null($user)) {
            $qb->andWhere('r.owner = :owner')
                    ->setParameter('owner', $user);
        }

        return $qb->getQuery()->getResult();
    }

    public function addFindByStateType(array $types, QueryBuilder $query, Instance $instance = null, BaseUser $user = null)
    {
        $query = $query->innerJoin('r.states', 's');

        if (count($types) > 0) {
            $query = $query->andWhere('s.type IN (:state_types)')
                    ->setParameter('state_types', $types);
        }

        if (!is_null($instance)) {
            $query = $query->andWhere('s.instance = :instance_id')
                    ->setParameter('instance_id', $instance->getId());
        }

        if (!is_null($user)) {
            $query = $query->andWhere('r.owner = :user_id OR r.librarian = :user_id')
                    ->setParameter('user_ud', $user->getId());
        }

        return $query;
    }

    public function findActiveForUser(BaseUser $user, Instance $instance)
    {
        $qb = $this->createQueryBuilder();

        return $this->addFindByStateType(array(
                            StateManager::STATE__CREATED,
                            StateManager::STATE__SEARCHED,
                            StateManager::STATE__REQUESTED,
                            StateManager::STATE__APPROVAL_PENDING,
                            StateManager::STATE__RECEIVED,
                                ), $qb, $instance, $user)
                        ->getQuery()
                        ->execute();
    }

}
