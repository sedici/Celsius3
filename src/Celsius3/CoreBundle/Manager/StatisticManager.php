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

namespace Celsius3\CoreBundle\Manager;

use Doctrine\ORM\EntityManager;

class StatisticManager
{
    private $em;
    private $statistic_data = array(
        'usersPerInstance' => array(
            'repository' => 'Celsius3CoreBundle:BaseUser',
        ),
        'newUsersPerInstance' => array(
            'repository' => 'Celsius3CoreBundle:BaseUser',
        ),
        'ordersPerStatePerInstance' => array(
            'repository' => 'Celsius3CoreBundle:State',
        ),
        'totalOrdersPerInstance' => array(
            'repository' => 'Celsius3CoreBundle:State',
        ),
    );

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function usersPerInstance()
    {
        $usersPerInstance = $this->em
                ->getRepository($this->statistic_data['usersPerInstance']['repository'])
                ->findUsersPerInstance();

        $response = array();
        foreach ($usersPerInstance as $instance) {
            $response[] = array(
                'label' => $this->em->getRepository('Celsius3CoreBundle:Instance')
                        ->find((string) $instance['_id'])->getAbbreviation(),
                'data' => $instance['value'],
            );
        }

        return $response;
    }

    public function newUsersPerInstance()
    {
        $newUsersPerInstance = $this->em
                ->getRepository($this->statistic_data['newUsersPerInstance']['repository'])
                ->findNewUsersPerInstance();

        $response = array();
        foreach ($newUsersPerInstance as $instance) {
            $response[] = array(
                'label' => $this->em->getRepository('Celsius3CoreBundle:Instance')
                        ->find((string) $instance['_id'])->getAbbreviation(),
                'data' => $instance['value'],
            );
        }

        return $response;
    }

    public function ordersPerInstance()
    {
        $ordersPerInstance = $this->em
                ->getRepository($this->statistic_data['ordersPerInstance']['repository'])
                ->findOrdersPerStatePerInstance();

        $response = array();
        foreach ($ordersPerInstance as $instance) {
            $response[] = array(
                'label' => $this->em->getRepository('Celsius3CoreBundle:Instance')
                        ->find((string) $instance['_id'])->getAbbreviation(),
                'data' => $instance['value'],
            );
        }

        return $response;
    }

    public function getOrderUserTableData()
    {
        $instances = $this->em->getRepository('Celsius3CoreBundle:Instance')
                ->findBy(array(
            'enabled' => true,
        ));

        $data = array(
            'pendingOrders' => $this->em
                    ->getRepository($this->statistic_data['ordersPerStatePerInstance']['repository'])
                    ->findOrdersPerStatePerInstance(StateManager::STATE__CREATED),
            'deliveredOrders' => $this->em
                    ->getRepository($this->statistic_data['ordersPerStatePerInstance']['repository'])
                    ->findOrdersPerStatePerInstance(StateManager::STATE__DELIVERED),
            'totalOrders' => $this->em
                    ->getRepository($this->statistic_data['totalOrdersPerInstance']['repository'])
                    ->findTotalOrdersPerInstance(),
            'pendingUsers' => $this->em
                    ->getRepository($this->statistic_data['newUsersPerInstance']['repository'])
                    ->findNewUsersPerInstance(),
            'totalUsers' => $this->em
                    ->getRepository($this->statistic_data['usersPerInstance']['repository'])
                    ->findUsersPerInstance(),
        );

        $response = array();
        foreach ($instances as $instance) {
            $response[$instance->getId()] = array(
                'name' => $instance->getName(),
                'country' => $instance->getOwnerInstitutions()->first() ? ( $instance->getOwnerInstitutions()->first()->getCountry() ? $instance->getOwnerInstitutions()->first()->getCountry()->getName() : '') : '',
            );
        }
        foreach ($data as $key => $item) {
            foreach ($item as $instance) {
                $response[$instance[1]][$key] = $instance['c'];
            }
        }

        return array_values($response);
    }
}
