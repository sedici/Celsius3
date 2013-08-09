<?php

namespace Celsius3\CoreBundle\Manager;

use Doctrine\ODM\MongoDB\DocumentManager;

class StatisticManager
{

    private $dm;
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

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
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
        $response = array();
        foreach ($instances as $instance) {
            $response[$instance->getId()] = array(
                'name' => $instance->getName(),
                'country' => $instance->getOwnerInstitutions()->first()->getCountry() ? $instance->getOwnerInstitutions()->first()->getCountry()->getName() : '',
            );
        }
        foreach ($data as $key => $item) {
            foreach ($item as $instance) {
                $response[(string) $instance['_id']][$key] = $instance['value'];
            }
        }

        return array_values($response);
    }

}