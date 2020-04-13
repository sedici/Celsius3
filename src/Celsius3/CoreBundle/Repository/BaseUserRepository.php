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

use Doctrine\ORM\QueryBuilder;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Manager\UserManager;

/**
 * BaseUserRepository.
 */
class BaseUserRepository extends BaseRepository
{
    public function findAdmins(Instance $instance)
    {
        return $this->createQueryBuilder('u')
                        ->where('u.instance = :instance_id')
                        ->andWhere('u.roles LIKE :roles')
                        ->setParameter('instance_id', $instance->getId())
                        ->setParameter('roles', '%"'.UserManager::ROLE_ADMIN.'"%')
                        ->getQuery()
                        ->getResult();
    }


    public function findManagerOrder(Instance $instance)
    {
        return $this->createQueryBuilder('u')
            ->where('u.instance = :instance_id')
            ->andWhere('u.roles LIKE :roles')
            ->setParameter('instance_id', $instance->getId())
            ->setParameter('roles', '%"'.UserManager::ROLE_ORDER_MANAGER.'"%')
            ->getQuery()
            ->getResult();
    }



    public function findPendingUsers(Instance $instance = null)
    {
        $qb = $this->createQueryBuilder('u')
                ->where('u.enabled = false')
                ->andWhere('u.locked = false');

        if (!is_null($instance)) {
            $qb = $qb->andWhere('u.instance = :instance_id')
                    ->setParameter('instance_id', $instance->getId());
        }

        return $qb->getQuery()->getResult();
    }

    public function countUsers(Instance $instance = null)
    {
        $qb = $this->createQueryBuilder('u')
                ->where('u.enabled = false')
                ->andWhere('u.locked = false');

        if (!is_null($instance)) {
            $qb = $qb->andWhere('u.instance = :instance_id')
                    ->setParameter('instance_id', $instance->getId());
        }

        return array(
            'pending' => count($qb->getQuery()->getResult()),
        );
    }

    public function findByTerm($term, Instance $instance = null, $limit = null, array $institutions = array())
    {
        $qb = $this->createQueryBuilder('u');

        $qb = $qb->where($qb->expr()->like('u.name', $qb->expr()->literal('%'.$term.'%')))
                ->orWhere($qb->expr()->like('u.surname', $qb->expr()->literal('%'.$term.'%')))
                ->orWhere($qb->expr()->like('u.username', $qb->expr()->literal('%'.$term.'%')))
                ->orWhere($qb->expr()->like('u.email', $qb->expr()->literal('%'.$term.'%')));

        if (!is_null($instance)) {
            $qb = $qb->andWhere('u.instance = :instance_id')
                    ->setParameter('instance_id', $instance->getId());
        }

        if (count($institutions) > 0) {
            $qb = $qb->andWhere('u.institution IN (:institution_ids)')
                    ->setParameter('institution_ids', $institutions);
        }

        if (!is_null($limit)) {
            $qb = $qb->setMaxResults(10);
        }

        return $qb->getQuery();
    }

    public function addFindByStateType(array $data, QueryBuilder $query, Instance $instance = null)
    {
        $alias = $query->getRootAliases()[0];
        $condition = '';
        foreach ($data as $value) {
            if ($condition !== '') {
                $condition .= ' OR ';
            }
            switch ($value) {
                case 'enabled':
                    $condition .= '('.$alias.'.enabled = true AND '.$alias.'.locked = false)';
                    break;
                case 'pending':
                    $condition .= '('.$alias.'.enabled = false AND '.$alias.'.locked = false)';
                    break;
                case 'rejected':
                    $condition .= '('.$alias.'.locked = true)';
                    break;
            }
        }
        $query = $query->andWhere($condition);

        if (!is_null($instance)) {
            $query = $query->andWhere($alias.'.instance = :instance_id')
                    ->setParameter('instance_id', $instance->getId());
        }

        return $query;
    }

    public function addFindByRole($data, QueryBuilder $query, Instance $instance = null)
    {
        $alias = $query->getRootAliases()[0];

        $query = $query->andWhere($alias.'.roles LIKE :role')
                ->setParameter('role', '%"'.$data.'"%');

        if (!is_null($instance)) {
            $query = $query->andWhere($alias.'.instance = :instance_id')
                    ->setParameter('instance_id', $instance->getId());
        }

        return $query;
    }

    public function findUsersPerInstance()
    {
        return $this->createQueryBuilder('u')
                        ->select('IDENTITY(u.instance), COUNT(u.id) as c')
                        ->groupBy('u.instance')
                        ->getQuery()
                        ->getResult();
    }

    public function findNewUsersPerInstance()
    {
        return $this->createQueryBuilder('u')
                        ->select('IDENTITY(u.instance), COUNT(u.id) as c')
                        ->where('u.enabled = false')
                        ->groupBy('u.instance')
                        ->getQuery()
                        ->getResult();
    }

    public function countNewUsersFor($instance, $initialYear, $finalYear)
    {
        $qb = $this->createQueryBuilder('user');

        if ($initialYear === $finalYear) {
            $qb = $qb->select('MONTH(user.createdAt) axisValue')
                            ->andWhere('YEAR(user.createdAt) = :y')->setParameter('y', $initialYear);
        } elseif ($initialYear < $finalYear) {
            $qb = $qb->select('YEAR(user.createdAt) axisValue')
                            ->andHaving('axisValue >= :initialYear')->setParameter('initialYear', $initialYear)
                            ->andHaving('axisValue <= :finalYear')->setParameter('finalYear', $finalYear);
        }

        if (!is_null($instance)) {
            $qb = $qb->andWhere('user.instance = :instance')->setParameter('instance', $instance);
        }

        $qb = $qb->addSelect('COUNT(user.id) newUsers')
                ->groupBy('axisValue')
                ->orderBy('axisValue', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function getTotalUsersUntil($instance, $initialYear, $finalYear, $axisValue)
    {
        $date = \DateTime::createFromFormat('Y', $axisValue);
        if ($initialYear === $finalYear) {
            $date = \DateTime::createFromFormat('Y', $initialYear);
            $date->add(new \DateInterval('P'.$axisValue.'M'));
        }

        $qb = $this->createQueryBuilder('user');

        if (!is_null($instance)) {
            $qb = $qb->where('user.instance = :instance')->setParameter('instance', $instance);
        }

        return $qb->select('COUNT(user.id) newUsers')
                        ->andWhere('user.createdAt <= :date')->setParameter('date', $date)
                        ->getQuery()->getSingleResult();
    }

    public function getYears($instance)
    {
        return $this->createQueryBuilder('user')
                        ->select('YEAR(user.createdAt) year')
                        ->where('user.instance = :instance')->setParameter('instance', $instance)
                        ->groupBy('year')
                        ->orderBy('year')
                        ->getQuery()
                        ->getResult();
    }

    public function getUsersWithMessageNotification($type, $receivers)
    {
        $qb = $this->createQueryBuilder('u')
                        ->select('u')
                        ->join('u.notificationSettings', 'ns')
                        ->where('u IN (:receivers)')->setParameter('receivers', $receivers)
                        ->andWhere('ns.type = :type')->setParameter('type', 'message_notification');

        if ($type === 'interface') {
            $qb->andWhere('ns.subscribedToInterfaceNotifications = :uin')->setParameter('uin', true);
        }

        if ($type === 'email') {
            $qb->andWhere('ns.subscribedToEmailNotifications = :ein')->setParameter('ein', true);
        }

        return $qb->getQuery()->execute();
    }

    public function getAdminsWithUserNotification($type, $instance)
    {
        $qb = $this->createQueryBuilder('u');
        $qb = $qb->select('u')
                        ->join('u.notificationSettings', 'ns')
                        ->where('u.roles LIKE :roles')
                        ->setParameter('roles', '%ROLE_ADMIN%')
                        ->andWhere('ns.type = :type')->setParameter('type', 'user_notification')
                        ->andWhere('ns.instance = :instance')->setParameter('instance', $instance);
        if ($type === 'interface') {
            $qb = $qb->andWhere('ns.subscribedToInterfaceNotifications = :uin')->setParameter('uin', true);
        }
        if ($type === 'email') {
            $qb = $qb->andWhere('ns.subscribedToEmailNotifications = :uen')->setParameter('uen', true);
        }

        return $qb->getQuery()->execute();
    }

    public function getUsersWithEventNotification($type, $event, $event_type)
    {
        $qb = $this->createQueryBuilder('u');

        $qb = $qb->select('u')
                        ->join('u.notificationSettings', 'ns')
                        ->where('u.roles LIKE :roles')
                        ->setParameter('roles', '%ROLE_ADMIN%')
                        ->orWhere('u.id = :owner_id')->setParameter('owner_id', $event->getRequest()->getOwner()->getId())
                        ->andWhere('ns.type = :type')->setParameter('type', $event_type.'_notification')
                        ->andWhere('ns.instance = :instance')->setParameter('instance', $event->getInstance());

        if ($type === 'interface') {
            $qb = $qb->andWhere('ns.subscribedToInterfaceNotifications = :uin')->setParameter('uin', true);
        }
        if ($type === 'email') {
            $qb = $qb->andWhere('ns.subscribedToEmailNotifications = :uen')->setParameter('uen', true);
        }

        return $qb->getQuery()->execute();
    }

    public function findAllAdmins()
    {
        return $this->createQueryBuilder('u')
                        ->andWhere('u.roles LIKE :roles')
                        ->setParameter('roles', '%"'.UserManager::ROLE_ADMIN.'"%')
                        ->getQuery()
                        ->getResult();
    }

    public function getInstitutionUsers($id)
    {
        return $this->createQueryBuilder('u')
                        ->select('u')
                        ->where('u.institution = :institution_id')
                        ->setParameter('institution_id', $id)
                        ->orderBy('u.surname')
                        ->addOrderBy('u.name')
                        ->getQuery()->getResult();
    }

    public function findUsersFrom(Instance $instance, $startDate)
    {
        $qb = $this->createQueryBuilder('u')
          ->where('u.instance = :instance_id')
          ->setParameter('instance_id', $instance->getId());

        if (!is_null($startDate)) {
            $qb->andWhere('u.createdAt >= :date')->setParameter('date', $startDate);
        }

        return $qb;
    }

    public function findPdfUsers(Instance $instance)
    {
        $qb = $this->createQueryBuilder('u')
              ->where('u.instance = :instance_id')
              ->andWhere('u.pdf = :pdf')
              ->setParameter('instance_id', $instance->getId())
              ->setParameter('pdf', true);

        return $qb;
    }

    public function findUsers($ids)
    {
        return $this->createQueryBuilder('u')
                    ->where('u.id IN (:elements)')
                    ->setParameter('elements', $ids)
                    ->getQuery()->getResult();
    }

    public function findUserInstanceAdmins(BaseUser $user, $role)
    {
        return $this->createQueryBuilder('u')
                    ->where('u.id <> :id')
                    ->andWhere('u.instance = :instance_id')
                    ->andWhere('u.roles LIKE :role')
                    ->setParameter('id', $user->getId())
                    ->setParameter('instance_id', $user->getInstance()->getId())
                    ->setParameter('role', '%'.$role.'%')
                    ->getQuery()->getResult();
    }


    public function findByUserInstanceAndRole($user, $role){

        return $this->createQueryBuilder('u')
            ->where('u.id <> :id')
            ->andWhere('u.instance = :instance_id')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('id', $user->getId())
            ->setParameter('instance_id', $user->getInstance()->getId())
            ->setParameter('role', '%' . $role . '%')
            ->getQuery()
            ->getResult();
    }


    public function addFindByCountry($data, QueryBuilder $query, Instance $instance = null)
    {
        $alias = $query->getRootAliases()[0];

        $query = $query->join($alias . '.institution', 'i')
            ->join('i.country', 'c')
            ->andWhere('c.id = :country')
            ->setParameter('country', $data);

        if (!is_null($instance)) {
            $query = $query->andWhere($alias . '.instance = :instance_id')
                ->setParameter('instance_id', $instance->getId());
        }

        return $query;
    }

    public function addFindByCity($data, QueryBuilder $query, Instance $instance = null)
    {
        $alias = $query->getRootAliases()[0];

        $query = $query->join('i.city', 'cy')
            ->andWhere('cy.id = :city')
            ->setParameter('city', $data);

        if (!is_null($instance)) {
            $query = $query->andWhere($alias . '.instance = :instance_id')
                ->setParameter('instance_id', $instance->getId());
        }

        return $query;
    }
}
