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

use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Manager\InstanceManager;
use Celsius3\CoreBundle\Document\Analytics\UserAnalytics;

class StatisticManager
{

    private $dm;
    private $instanceManager;
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

    public function __construct(DocumentManager $dm, InstanceManager $instanceManager)
    {
        $this->dm = $dm;
        $this->instanceManager = $instanceManager;
    }

    public function usersPerInstance()
    {
        $usersPerInstance = $this->dm
                ->getRepository($this->statistic_data['usersPerInstance']['repository'])
                ->findUsersPerInstance();

        $response = array();
        foreach ($usersPerInstance as $instance) {
            $response[] = array(
                'label' => $this->dm->getRepository('Celsius3CoreBundle:Instance')
                        ->find((string) $instance['_id'])->getAbbreviation(),
                'data' => $instance['value'],
            );
        }

        return $response;
    }

    public function newUsersPerInstance()
    {
        $newUsersPerInstance = $this->dm
                ->getRepository($this->statistic_data['newUsersPerInstance']['repository'])
                ->findNewUsersPerInstance();

        $response = array();
        foreach ($newUsersPerInstance as $instance) {
            $response[] = array(
                'label' => $this->dm->getRepository('Celsius3CoreBundle:Instance')
                        ->find((string) $instance['_id'])->getAbbreviation(),
                'data' => $instance['value'],
            );
        }

        return $response;
    }

    public function ordersPerInstance()
    {
        $ordersPerInstance = $this->dm
                ->getRepository($this->statistic_data['ordersPerInstance']['repository'])
                ->findOrdersPerStatePerInstance();

        $response = array();
        foreach ($ordersPerInstance as $instance) {
            $response[] = array(
                'label' => $this->dm->getRepository('Celsius3CoreBundle:Instance')
                        ->find((string) $instance['_id'])->getAbbreviation(),
                'data' => $instance['value'],
            );
        }

        return $response;
    }

    public function getOrderUserTableData()
    {
        $instances = $this->dm->getRepository('Celsius3CoreBundle:Instance')
                ->findBy(array(
            'enabled' => true,
        ));

        $data = array(
            'pendingOrders' => $this->dm
                    ->getRepository($this->statistic_data['ordersPerStatePerInstance']['repository'])
                    ->findOrdersPerStatePerInstance(StateManager::STATE__CREATED),
            'deliveredOrders' => $this->dm
                    ->getRepository($this->statistic_data['ordersPerStatePerInstance']['repository'])
                    ->findOrdersPerStatePerInstance(StateManager::STATE__DELIVERED),
            'totalOrders' => $this->dm
                    ->getRepository($this->statistic_data['totalOrdersPerInstance']['repository'])
                    ->findTotalOrdersPerInstance(),
            'pendingUsers' => $this->dm
                    ->getRepository($this->statistic_data['newUsersPerInstance']['repository'])
                    ->findNewUsersPerInstance(),
            'totalUsers' => $this->dm
                    ->getRepository($this->statistic_data['usersPerInstance']['repository'])
                    ->findUsersPerInstance(),
        );

        $this->dm->getRepository('Celsius3CoreBundle:State')
                ->findTotalTime();

        $response = array();
        foreach ($instances as $instance) {
            $response[$instance->getId()] = array(
                'name' => $instance->getName(),
                'country' => $instance->getOwnerInstitutions()->first() ? ( $instance->getOwnerInstitutions()->first()->getCountry() ? $instance->getOwnerInstitutions()->first()->getCountry()->getName() : '') : '',
            );
        }
        foreach ($data as $key => $item) {
            foreach ($item as $instance) {
                $response[(string) $instance['_id']][$key] = $instance['value'];
            }
        }

        return array_values($response);
    }

    public function calculateOrdersAnalytics(Instance $instance)
    {
        //Recorrer paises, instituciones, usuarios llamando calculateOrdersCounters()
        $countries = $this->dm->getRepository('Celsius3CoreBundle:Country')
                ->findForInstanceAndGlobal($instance, $this->instanceManager->getDirectory());

        foreach ($countries as $country) {
            $institutions = $this->dm->getRepository('Celsius3CoreBundle:Institution')->findForInstanceAndGlobal($instance, $this->instanceManager->getDirectory(), null, $country->getId());
            foreach ($institutions as $institution) {
                
            }
        }
    }

    public function calculateOrdersCounters(Instance $instance)
    {
        $count = $this->dm->getRepository('Celsius3CoreBundle:State')->countByYear();
    }

    public function calculateUsersAnalytics()
    {
        $usersCounts = $this->dm->getRepository('Celsius3CoreBundle:BaseUser')->countUsersPerInstance();
        $activeUsersCount = $this->dm->getRepository('Celsius3CoreBundle:Request')->countActiveUsers();
        $instances = $this->dm->getRepository('Celsius3CoreBundle:Instance')->findAllExceptDirectory();

        $usersCountsArray = array();
        $this->collectActiveUsersCounts($activeUsersCount, $usersCountsArray);
        $this->collectUsersCounts($usersCounts, $usersCountsArray);
        $this->updateCounts($instances, $usersCountsArray);
    }

    private function collectActiveUsersCounts($activeUsersCount, &$usersCountArray)
    {
        foreach ($activeUsersCount as $count) {
            $usersCountArray[(String) $count['_id']['instance_id']][$count['_id']['year']]['months'][$count['_id']['month']]['activeUsers'] = (Integer) count(array_unique($count['value']['users']));
            $usersCountArray[(String) $count['_id']['instance_id']][$count['_id']['year']]['months'][$count['_id']['month']]['activeUsersIds'] = array_unique($count['value']['users']);
        }
    }

    private function collectUsersCounts($usersCounts, &$usersCountArray)
    {
        foreach ($usersCounts as $count) {
            $usersCountArray[(String) $count['_id']['instance_id']][$count['_id']['year']]['months'][$count['_id']['month']]['newUsers'] = (Integer) $count['value']['count'];
        }
    }

    private function updateCounts($instances, &$usersCountArray)
    {
        foreach ($usersCountArray as $instance => $instanceValue) {
            foreach ($instanceValue as $year => $yearValue) {
                $totalActiveUsers = array();
                foreach ($yearValue as $monthsValue) {
                    foreach ($monthsValue as $month => $counts) {
                        $monthsValue[$month]['month'] = $month + 1;
                        $totalActiveUsers = (isset($counts['activeUsersIds'])) ? array_merge($totalActiveUsers, $counts['activeUsersIds']) : $totalActiveUsers;

                        $time = new \DateTime($year . '-' . ($month + 1));
                        $time->add(date_interval_create_from_date_string('1 months'));
                        $date = new \MongoDate($time->getTimestamp());
                        $monthsValue[$month]['totalUsers'] = $this->dm->getRepository('Celsius3CoreBundle:BaseUser')->newUsersCountLT($date);
                    }
                }
                $userAnalytics = $this->dm->getRepository('Celsius3CoreBundle:Analytics\\UserAnalytics')->findOneBy(array('instance.id' => $instance, 'year' => $year));
                $userAnalytics = ($userAnalytics === NULL) ? new UserAnalytics() : $userAnalytics;

                $userAnalytics->setInstance($this->dm->getRepository('Celsius3CoreBundle:Instance')->findOneBy(array('id' => $instance)));
                $userAnalytics->setYear($year);
                $userAnalytics->setCounters($monthsValue);
                $userAnalytics->setYearNewUsers($monthsValue[max(array_keys($monthsValue))]['totalUsers']);
                $userAnalytics->setYearActiveUsers(count(array_unique($totalActiveUsers)));
                
                $this->dm->persist($userAnalytics);
                $this->dm->flush();
            }
        }
    }

}
