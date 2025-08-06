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

use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Instance;
use Celsius3\Entity\Order;
use Celsius3\Manager\StateManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository<\Celsius3\Entity\Order>
 */
class OrderRepository extends ServiceEntityRepository implements OrderRepositoryInterface
{
    public function __construct(private readonly ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findBaseDoUnionEntities($main, $ids): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.id IN (:ids)')
            ->andWhere('e.id <> :id')
            ->setParameter('ids', $ids)
            ->setParameter('id', $main->getId())
            ->getQuery()->getResult();
    }

    public function union(string $field, int $main_id, array $elements): int
    {
        return $this->createQueryBuilder('e')
            ->update()
            ->set('e.' . $field, ':main_id')
            ->where('e.' . $field . ' IN (:ids)')
            ->setParameter('ids', $elements)
            ->setParameter('main_id', $main_id)
            ->getQuery()->execute();
    }

    public function deleteUnitedEntities(array $elements): int
    {
        return $this->createQueryBuilder('e')
            ->delete()
            ->where('e.id IN (:ids)')
            ->setParameter('ids', $elements)
            ->getQuery()->execute();
    }

    public function findByTerm(
        string $term,
        ?Instance $instance = null,
        ?string $type = null,
        ?int $limit = null,
        ?string $state = null
    ): QueryBuilder {
        $qb = $this->createQueryBuilder('o')
            ->addSelect('r')
            ->join('o.requests', 'r')
            ->addSelect('s')
            ->innerJoin('r.states', 's');

        if ($type !== null) {
            $secondary = array_map(
                fn($entity) => $entity->getId(),
                $this->getEntityManager()
                    ->getRepository('Celsius3:' . $type)
                    ->findByTerm($term, $instance)
                    ->getResult()
            );

            match ($type) {
                'BaseUser' => $qb->andWhere($qb->expr()->in('r.owner', $secondary)),
                'JournalType' => $qb->andWhere($qb->expr()->in('o.materialData', $secondary)),
                default => $qb->andWhere('r.id = -1'),
            };
        } else {
            $qb = $qb->join('o.materialData', 'md')
                ->addSelect('md')
                ->orWhere($qb->expr()->like('o.code', $qb->expr()->literal('%' . $term . '%')))
                ->orWhere($qb->expr()->like('md.title', $qb->expr()->literal('%' . $term . '%')))
                ->orWhere($qb->expr()->like('md.authors', $qb->expr()->literal('%' . $term . '%')))
                ->orWhere($qb->expr()->like('md.year', $qb->expr()->literal('%' . $term . '%')));
        }

        if ($instance !== null) {
            $qb = $qb->andWhere('r.instance = :instance')
                ->setParameter('instance', $instance);
        }

        if ($state !== null && $state !== 'allStates') {
            $qb = $qb->andWhere('s.type = :type')
                ->setParameter('type', $state)
                ->andWhere('s.current = :current')
                ->setParameter('current', true);
        }

        if ($limit !== null) {
            $qb = $qb->setMaxResults($limit);
        }

        return $qb;
    }

    public function findOneForInstance(Instance $instance, string $id): ?Order
    {
        return $this
            ->createQueryBuilder('o')
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


    public function findForInstance(
        Instance $instance,
        ?BaseUser $user = null,
        $state = null,
        ?BaseUser $owner = null,
        $orderType = null
    ): QueryBuilder {
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
        } elseif ($state !== null) {
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

        if (($orderType !== null && !($orderType === 'allTypes'))) {
            $qb->andWhere('r.type = :order_type')
                ->setParameter('order_type', $orderType);
        }

        if ($user !== null) {
            if (!(is_array($state) && in_array(StateManager::STATE__CREATED, $state)) && !($state !== null
            && $state === StateManager::STATE__CREATED)) {
                $qb = $qb->andWhere('(r.operator = :user)')
                    ->setParameter('user', $user);
            }
        }

        if ($owner !== null) {
            $qb->andWhere('r.owner = :owner')
                ->setParameter('owner', $owner);
        }

        return $qb;
    }


    public function findByStateType(
        string $type,
        $startDate,
        ?BaseUser $user = null,
        ?Instance $instance = null
    ): array {
        return $this
            ->createQueryBuilder('o')
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
        string $type,
        ?\DateTime $startDate = null,
        ?BaseUser $user = null,
        ?Instance $instance = null,
        ?int $limit = null,
        ?int $offset = null
    ): array {
        $qb = $this->createQueryBuilder('o')
            ->addSelect('r')
            ->join('o.requests', 'r')
            ->join('r.states', 's')
            ->where('s.type = :type')
            ->setParameter('type', $type);

        if ($startDate !== null) {
            $qb->andWhere('s.createdAt >= :date')
                ->setParameter('date', $startDate);
        }

        if ($limit !== null && $offset !== null) {
            $qb->setMaxResults($limit)
                ->setFirstResult($offset)
                ->orderBy('r.createdAt', 'DESC');
        } else {
            $qb->setMaxResults(10)
                ->orderBy('r.createdAt', 'DESC');
        }

        if ($instance !== null) {
            $qb->andWhere('r.instance = :instance')
                ->setParameter('instance', $instance);
        }

        if ($user !== null) {
            $qb->andWhere('r.owner = :owner')
                ->setParameter('owner', $user);
        }

        return $qb->getQuery()->getResult();
    }


    public function addFindByRequestType(
        string|int|null $type,
        QueryBuilder $query,
        ?Instance $instance = null,
        ?BaseUser $user = null
    ): QueryBuilder {
        if (is_int($type))
            $type = match ($type) {
                0 => 'provision',
                1 => 'search',
                default => null,
            };

        if ($type !== null) $query = $query
            ->andWhere('r.type = :type')
            ->setParameter('type', $type, 'string');

        return $query;
    }


    public function findActiveForUser(
        BaseUser $user,
        Instance $instance
    ): array {
        $qb = $this->createQueryBuilder('o');

        return $this
            ->addFindByStateType(
                [
                    StateManager::STATE__CREATED,
                    StateManager::STATE__SEARCHED,
                    StateManager::STATE__REQUESTED,
                    StateManager::STATE__APPROVAL_PENDING,
                    StateManager::STATE__RECEIVED,
                ],
                $qb,
                $instance,
                $user
            )
            ->getQuery()
            ->getResult();
    }


    public function addFindByStateType(
        array $types,
        QueryBuilder $query,
        ?Instance $instance = null,
        ?BaseUser $user = null
    ): QueryBuilder {
        $query = $query->join('r.states', 's');

        if (!empty($types)) $query = $query
            ->andWhere('s.type IN (:state_types)')
            ->setParameter('state_types', $types)
            ->andWhere('s.current = true');

        if ($instance !== null) $query = $query
            ->andWhere('s.instance = :instance_id')
            ->setParameter('instance_id', $instance->getId());

        if ($user !== null) $query = $query
            ->andWhere('r.owner = :user_id OR r.librarian = :user_id')
            ->setParameter('user_id', $user->getId());

        return $query;
    }


    public function addFindByRequestInstance(
        $data,
        QueryBuilder $query,
        ?Instance $instance = null,
        ?BaseUser $user = null
    ): QueryBuilder {
        if ($data instanceof Instance) $query = $query
            ->andWhere('r.instance = :instance')
            ->setParameter('instance', $data->getId());

        return $query;
    }


    public function addFindByRequestOwner(
        $data,
        QueryBuilder $query,
        ?Instance $instance = null,
        ?BaseUser $user = null
    ): QueryBuilder {
        if ($data instanceof BaseUser) {
            $query = $query->andWhere('r.owner = :owner')
                ->setParameter('owner', $data->getId());
        }

        return $query;
    }


    public function listUserOrdersQuery(
        Instance $instance,
        BaseUser $user
    ): QueryBuilder {
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
