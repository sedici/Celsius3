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

use Doctrine\ODM\MongoDB\DocumentRepository;
use Celsius3\CoreBundle\Document\BaseUser;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Manager\StateManager;

class StateRepository extends DocumentRepository
{

    protected function getRequestIds($value)
    {
        return $value['_id'];
    }

    public function countOrders(Instance $instance = null, BaseUser $user = null)
    {
        $types = StateManager::$stateTypes;

        $result = array();
        foreach ($types as $type) {
            $qb = $this->createQueryBuilder()
                            ->hydrate(false)
                            ->field('isCurrent')->equals(true)
                            ->field('type')->equals($type);

            if (!is_null($instance)) {
                $qb = $qb->field('instance.id')->equals($instance->getId());
            }

            if (!is_null($user)) {
                $qb = $qb->addOr($qb->expr()->field('operator.id')->equals($user->getId()))
                        ->addOr($qb->expr()->field('operator.id')->equals(null));
            }

            $result[$type] = $qb->getQuery()->execute()->count();
        }

        return $result;
    }

    public function findOrdersPerStatePerInstance($state)
    {
        return $this->createQueryBuilder()
                        ->field('type')->equals($state)
                        ->field('isCurrent')->equals(true)
                        ->map('function () { emit(this.instance.$id, 1); }')
                        ->reduce('function (k, vals) {
                            var sum = 0;
                            for (var i in vals) {
                                sum += vals[i];
                            }

                            return sum;
                        }')
                        ->getQuery()
                        ->execute();
    }

    public function findTotalOrdersPerInstance()
    {
        return $this->createQueryBuilder()
                        ->field('isCurrent')->equals(true)
                        ->map('function () { emit(this.instance.$id, 1); }')
                        ->reduce('function (k, vals) {
                            var sum = 0;
                            for (var i in vals) {
                                sum += vals[i];
                            }

                            return sum;
                        }')
                        ->getQuery()
                        ->execute();
    }

    public function findTotalTime($first = StateManager::STATE__CREATED, $last = StateManager::STATE__REQUESTED, Instance $instance = null)
    {
        $qb = $this->createQueryBuilder()
                ->map('function () {
                            emit(this.request.$id, {date: this.date.getTime(), type: this.type});
                        }')
                ->reduce('function (key, values) {
                            var datos = {};
                            values.forEach(function(value) {
                                datos[value.type] = value.date;
                            });
                            return datos;
                        }')
                ->finalize('function (key, value) {
                            if (value.hasOwnProperty("date")) {
                                return null;
                            }
                            return Math.ceil(Math.abs((value["' . $last . '"] - value["' . $first . '"]) / (1000 * 60 * 60 * 24)));
                        }');

        if (!is_null($instance)) {
            $qb = $qb->field('instance.id')->equals($instance->getId());
        }

        $qb = array_filter($qb->addOr($qb->expr()->field('type')->equals($first))
                        ->addOr($qb->expr()->field('type')->equals($last))
                        ->getQuery()
                        ->execute()
                        ->toArray(), function($item) {
            return !is_null($item['value']);
        });

        var_dump($qb);
        die();
    }
}