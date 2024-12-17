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

namespace Celsius3\Manager;

use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Instance;
use Celsius3\Entity\State;
use Doctrine\ORM\EntityManagerInterface;

class StatisticManager
{
    private $em;
    private $statistic_data = [
        'usersPerInstance' => [
            'repository' => BaseUser::class,
        ],
        'newUsersPerInstance' => [
            'repository' => BaseUser::class,
        ],
        'ordersPerStatePerInstance' => [
            'repository' => State::class,
        ],
        'totalOrdersPerInstance' => [
            'repository' => State::class,
        ],
    ];

    public function __construct(EntityManagerInterface $em)
    { $this->em = $em; }

    public function usersPerInstance(): array
    {
        $usersPerInstance = $this->em
            ->getRepository($this->statistic_data['usersPerInstance']['repository'])
            ->findUsersPerInstance();

        $response = array();
        foreach ($usersPerInstance as $instance) {
            $response[] = [
                'label' => $this->em->getRepository(Instance::class)
                        ->find((string) $instance['_id'])->getAbbreviation(),
                'data' => $instance['value'],
            ];
        }

        return $response;
    }

    public function newUsersPerInstance(): array
    {
        $newUsersPerInstance = $this->em
            ->getRepository($this->statistic_data['newUsersPerInstance']['repository'])
            ->findNewUsersPerInstance();

        $response = array();
        foreach ($newUsersPerInstance as $instance) {
            $response[] = [
                'label' => $this->em->getRepository(Instance::class)
                        ->find((string) $instance['_id'])->getAbbreviation(),
                'data' => $instance['value'],
            ];
        }

        return $response;
    }

    public function ordersPerInstance()
    {
        $ordersPerInstance = $this->em
                ->getRepository($this->statistic_data['ordersPerInstance']['repository'])
                ->findOrdersPerStatePerInstance();

        $response = [];
        foreach ($ordersPerInstance as $instance) {
            $response[] = array(
                'label' => $this->em->getRepository(Instance::class)
                        ->find((string) $instance['_id'])->getAbbreviation(),
                'data' => $instance['value'],
            );
        }

        return $response;
    }

    public function getOrderUserTableData(): array
    {
        $instances = $this->em->getRepository(Instance::class)
                ->findBy([
            'enabled' => true,
        ]);

        $data = [
            'totalOrders' => $this->em
                ->getRepository($this->statistic_data['totalOrdersPerInstance']['repository'])
                ->findTotalOrdersPerInstance(),
            'provisionOrders' => $this->em
                ->getRepository($this->statistic_data['ordersPerStatePerInstance']['repository'])
                ->findOrdersPerStatesPerInstance([
                    StateManager::STATE__CREATED,
                    StateManager::STATE__SEARCHED,
                    StateManager::STATE__REQUESTED,
                ], 'provision'),
            'pendingOrders' => $this->em
                ->getRepository($this->statistic_data['ordersPerStatePerInstance']['repository'])
                ->findOrdersPerStatesPerInstance([StateManager::STATE__CREATED]),
            'searchedOrders' => $this->em
                ->getRepository($this->statistic_data['ordersPerStatePerInstance']['repository'])
                ->findOrdersPerStatesPerInstance([StateManager::STATE__SEARCHED]),
            'requestedOrders' => $this->em
                ->getRepository($this->statistic_data['ordersPerStatePerInstance']['repository'])
                ->findOrdersPerStatesPerInstance([StateManager::STATE__REQUESTED]),
            'satisfiedOrders' => $this->em
                ->getRepository($this->statistic_data['ordersPerStatePerInstance']['repository'])
                ->findOrdersPerStatesPerInstance([
                    StateManager::STATE__RECEIVED,
                    StateManager::STATE__DELIVERED,
            ]),
            'pendingUsers' => $this->em
                ->getRepository($this->statistic_data['newUsersPerInstance']['repository'])
                ->findNewUsersPerInstance(),
            'totalUsers' => $this->em
                ->getRepository($this->statistic_data['usersPerInstance']['repository'])
                ->findUsersPerInstance(),
        ];

        $response = [];
        foreach ($instances as $instance) {
            $response[$instance->getId()] = array(
                'name' => $instance->getName(),
                'country' => $instance->getOwnerInstitutions()->first() ? ( $instance->getOwnerInstitutions()->first()->getCountry() ? $instance->getOwnerInstitutions()->first()->getCountry()->getName() : '') : '',
            );
        }
        foreach ($data as $key => $item) {
            foreach ($item as $instance) {
                if (array_key_exists($instance[1], $response))
                    $response[$instance[1]][$key] = $instance['c'];
            }
        }

        return array_values($response);
    }
}
