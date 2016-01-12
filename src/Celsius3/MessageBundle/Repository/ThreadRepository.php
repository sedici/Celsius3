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

namespace Celsius3\MessageBundle\Repository;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Celsius3\CoreBundle\Entity\BaseUser;

class ThreadRepository extends EntityRepository
{

    public function applyExtraFilters(QueryBuilder $builder, Request $request, BaseUser $user)
    {
        if ($request->query->has('created_between') && $request->query->get('created_between') != '') {
            $between = $request->query->get('created_between');
            if ($request->query->has('and') && $request->query->get('and') != '') {
                $and = $request->query->get('and');
                $builder = $builder->where($builder->getRootAliases()[0] . '.createdAt BETWEEN :between AND :and')
                        ->setParameter('between', $between)
                        ->setParameter('and', $and);
            } else {
                $builder = $builder->where($builder->getRootAliases()[0] . '.createdAt >= :between')
                        ->setParameter('between', $between);
            }
        } elseif ($request->query->has('and') && $request->query->get('and') != '') {
            $and = $request->query->get('and');
            $builder = $builder->where($builder->getRootAliases()[0] . '.createdAt <= :and')
                    ->setParameter('and', $and);
        }

        if ($request->query->has('read')) {
            $builder = $builder->join($builder->getRootAliases()[0] . '.messages', 'm')
                    ->join('m.metadata', 'meta')
                    ->where('meta.isRead <> true')
                    ->where('meta.participant <> :user_id')
                    ->setParameter('user_id', $user->getId());
        }

        if ($request->query->has('unread')) {
            $builder = $builder->join($builder->getRootAliases()[0] . '.messages', 'm')
                    ->join('m.metadata', 'meta')
                    ->where('meta.isRead = true')
                    ->where('meta.participant = :user_id')
                    ->setParameter('user_id', $user->getId());
        }

        return $builder;
    }
}
