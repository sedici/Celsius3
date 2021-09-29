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

namespace Celsius3\Repository;

use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Entity\Order;
use Celsius3\Manager\StateManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * OrderRepository.
 */
class OrderRepository extends ServiceEntityRepository implements OrderRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
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

    public function union($field, $main_id, $elements)
    {
        return $this->createQueryBuilder('e')
            ->update()
            ->set('e.'.$field, ':main_id')
            ->where('e.'.$field.' IN (:ids)')
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

    public function findByTerm($term, Instance $instance = null, $type, $limit = null, $state = null)
    {
        $qb = $this->createQueryBuilder('o')
            ->addSelect('r')
            ->join('o.requests', 'r')
            ->addSelect('s')
            ->innerJoin('r.states', 's');

        if (!is_null($type)) {
            $secondary = array_map(
                function ($entity) {
                    return $entity->getId();
                },
                $this->getEntityManager()
                    ->getRepository('Celsius3CoreBundle:' . $type)
                    ->findByTerm($term, $instance)
                    ->getResult()
            );

            if ($type === 'BaseUser' && count($secondary) > 0) {
                $qb->andWhere($qb->expr()->in('r.owner', $secondary));
            } elseif ($type === 'JournalType' && count($secondary) > 0) {
                $qb->andWhere($qb->expr()->in('o.materialData', $secondary));
            } else {
                // Esta condición es necesaria para evitar traer todos los resultados cuando
                // no hay usuarios o revistas que coincidan con los criterios antes buscados.
                $qb->andWhere('r.id = -1');
            }
        } else {
            $qb = $qb->join('o.materialData', 'md')
                ->addSelect('md')
                ->orWhere($qb->expr()->like('o.code', $qb->expr()->literal('%' . $term . '%')))
                ->orWhere($qb->expr()->like('md.title', $qb->expr()->literal('%' . $term . '%')))
                ->orWhere($qb->expr()->like('md.authors', $qb->expr()->literal('%' . $term . '%')))
                ->orWhere($qb->expr()->like('md.year', $qb->expr()->literal('%' . $term . '%')));
        }

        if (!is_null($instance)) {
            $qb = $qb->andWhere('r.instance = :instance')
                ->setParameter('instance', $instance);
        }

        if (!is_null($state) && $state !== 'allStates') {
            $qb = $qb->andWhere('s.type = :type')
                ->setParameter(':type', $state)
                ->andWhere('s.current = :current')
                ->setParameter(':current', true);
        }

        if (!is_null($limit)) {
            $qb = $qb->setMaxResults($limit);
        }

        return $qb->getQuery();
    }

    public function findForInstance(
        Instance $instance,
        BaseUser $user = null,
        $state = null,
        BaseUser $owner = null,
        $orderType = null
    ) {
        $qb = $this->createQueryBuilder('o')
            ->select('o, r, s, e, m, ow, op, i')
            ->join('o.requests', 'r')
            ->join('r.states', 's')
            ->join('r.events', 'e')
            ->leftJoin('r.owner', 'ow')
            ->leftJoin('r.operator', 'op')
            ->leftJoin('ow.institution', 'i')
            ->join('o.materialData', 'm')
            ->where('s.current = true')
            ->andWhere('r.instance = :instance')
            ->setParameter('instance', $instance);

        if (is_array($state) && count($state) > 0) {
            //            if (in_array(StateManager::STATE__REQUESTED, $state)) {
//                $qb->andWhere('s.searchPending = :searchPending')
//                    ->setParameter('searchPending', false);
//            }
            if (in_array(StateManager::STATE__SEARCHED, $state)) {
                $qb->andWhere(
                    '(s.type IN (:state_types) OR (s.type = :requested AND s.searchPending = :searchPending))'
                )
                    ->setParameter('state_types', $state)
                    ->setParameter('requested', StateManager::STATE__REQUESTED)
                    ->setParameter('searchPending', true);
            } else {
                $qb->andWhere('s.type IN (:state_types)')
                    ->setParameter('state_types', $state);
            }
        } elseif (!is_null($state)) {
            if (StateManager::STATE__REQUESTED === $state) {
                $qb->andWhere('s.searchPending = :searchPendind')->setParameter('searchPending', false);
            }

            if (StateManager::STATE__SEARCHED === $state) {
                $qb->andWhere('(s.type = :state_type OR (s.type = :requested AND s.searchPending = :searchPending))')
                    ->setParameter('state_type', $state)
                    ->setParameter('requested', StateManager::STATE__REQUESTED)
                    ->setParameter('searchPending', true);
            } else {
                $qb->andWhere('s.type = :state_type')
                    ->setParameter('state_type', $state);
            }
        }

        if ((!is_null($orderType) && !($orderType === 'allTypes'))) {
            $qb->andWhere('r.type = :order_type')
                ->setParameter('order_type', $orderType);
        }

        if (!is_null($user)) {
            if (!(is_array($state) && in_array(StateManager::STATE__CREATED, $state)) && !(!is_null(
                $state
            ) && $state === StateManager::STATE__CREATED)) {
                $qb = $qb->andWhere('(r.operator = :user)')
                    ->setParameter('user', $user);
            }
        }

        if (!is_null($owner)) {
            $qb->andWhere('r.owner = :owner')
                ->setParameter('owner', $owner);
        }

        return $qb;
    }

    public function findOneForInstance(Instance $instance, $id)
    {
        return $this->createQueryBuilder('o')
            ->select('o, r, s, e, m, ow, op, f')
            ->join('o.requests', 'r')
            ->join('r.states', 's')
            ->join('r.events', 'e')
            ->leftJoin('r.files', 'f')
            ->leftJoin('r.owner', 'ow')
            ->leftJoin('r.operator', 'op')
            ->join('o.materialData', 'm')
            ->where('o.id = :id')
            ->andWhere('r.instance = :instance_id')
            ->setParameter('id', $id)
            ->setParameter('instance_id', $instance->getId())
            ->getQuery()->getOneOrNullResult();
    }

    public function findByStateType($type, $startDate, BaseUser $user = null, Instance $instance = null)
    {
        return $this->createQueryBuilder('o')
            ->addSelect('r')
            ->join('o.requests', 'r')
            ->join('r.states', 's')
            ->where('s.type = :type')
            ->andWhere('r.instance = :instance_id')
            ->andWhere('r.owner = :owner_id')
            ->andWhere('s.createdAt >= :date')
            ->setParameter('type', $type)
            ->setParameter('instance_id', $instance->getId())
            ->setParameter('owner_id', $user->getId())
            ->setParameter('date', $startDate)
            ->getQuery()
            ->getResult();
    }

    public function findOrdersByStateType(
        $type,
        $startDate,
        BaseUser $user = null,
        Instance $instance = null,
        $limit = null,
        $offset = null
    ) {
        $qb = $this->createQueryBuilder('o')
            ->addSelect('r')
            ->join('o.requests', 'r')
            ->join('r.states', 's')
            ->where('s.type = :type')
            ->setParameter('type', $type);

        if (!is_null($startDate)) {
            $qb->andWhere('s.createdAt >= :date')
                ->setParameter('date', $startDate);
        } elseif (!is_null($limit) && !is_null($offset)) {
            $qb->setMaxResults($limit)
                ->setFirstResult($offset)
                ->orderBy('r.createdAt', 'DESC');
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

    public function addFindByRequestType($type, QueryBuilder $query, Instance $instance = null, BaseUser $user = null)
    {
        if (intval($type) === 0) {
            $type = 'provision';
        } elseif (intval($type) === 1) {
            $type = 'search';
        } else {
            $type = null;
        }

        if (!is_null($type)) {
            $query = $query->andWhere('r.type = :type')
                ->setParameter('type', $type, 'string');
        }

        return $query;
    }

    public function findActiveForUser(BaseUser $user, Instance $instance)
    {
        $qb = $this->createQueryBuilder('o');

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

    public function addFindByStateType(
        array $types,
        QueryBuilder $query,
        Instance $instance = null,
        BaseUser $user = null
    ) {
        $query = $query->join('r.states', 's');

        if (count($types) > 0) {
            $query = $query->andWhere('s.type IN (:state_types)')
                ->setParameter('state_types', $types)
                ->andWhere('s.current = true');
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

    public function addFindByRequestInstance(
        $data,
        QueryBuilder $query,
        Instance $instance = null,
        BaseUser $user = null
    ) {
        if ($data instanceof Instance) {
            $query = $query->andWhere('r.instance = :instance')
                ->setParameter('instance', $data->getId());
        }

        return $query;
    }

    public function addFindByRequestOwner($data, QueryBuilder $query, Instance $instance = null, BaseUser $user = null)
    {
        if ($data instanceof BaseUser) {
            $query = $query->andWhere('r.owner = :owner')
                ->setParameter('owner', $data->getId());
        }

        return $query;
    }

    public function listUserOrdersQuery(Instance $instance, BaseUser $user)
    {
        return $this
            ->createQueryBuilder('e')
            ->join('e.originalRequest', 'r')
            ->join('e.materialData', 'm')
            ->where('r.instance = :instance')
            ->setParameter('instance', $instance->getId())
            ->andWhere('r.owner = :owner OR r.librarian = :owner')
            ->setParameter('owner', $user->getId());
    }

    public function findUserOrder(int $id, Instance $instance, BaseUser $user): Order
    {
        $qb = $this->createQueryBuilder('e')
            ->join('e.originalRequest', 'r')
            ->where('r.instance = :instance')
            ->setParameter('instance', $instance->getId());

        $qb = $qb->orWhere($qb->expr()->eq('r.owner', ':owner'))->setParameter('owner', $user->getId());
        $qb = $qb->orWhere(
            $qb->expr()->eq('r.librarian', ':librarian')
        )->setParameter(
            'librarian',
            $user->getId()
        );

        return $qb->andWhere('e.id = :id')->setParameter('id', $id)->getQuery()->getSingleResult();
    }
}
