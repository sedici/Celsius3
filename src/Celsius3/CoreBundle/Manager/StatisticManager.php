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
        'ordersPerInstance' => array(
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
                ->findAll();

        $pendingOrdersPerInstance = $this->dm
                ->getRepository($this->statistic_data['ordersPerInstance']['repository'])
                ->findOrdersPerStatePerInstance(StateManager::STATE__CREATED);
        $deliveredOrdersPerInstance = $this->dm
                ->getRepository($this->statistic_data['ordersPerInstance']['repository'])
                ->findOrdersPerStatePerInstance(StateManager::STATE__DELIVERED);
        $totalOrdersPerInstance = $this->dm
                ->getRepository($this->statistic_data['ordersPerInstance']['repository'])
                ->findTotalOrdersPerInstance();

        $usersPerInstance = $this->dm
                ->getRepository($this->statistic_data['usersPerInstance']['repository'])
                ->findUsersPerInstance();
        $newUsersPerInstance = $this->dm
                ->getRepository($this->statistic_data['newUsersPerInstance']['repository'])
                ->findNewUsersPerInstance();
        
        $response = array();
        foreach ($instances as $instance) {
            $response[] = array(
                'name' => $instance->getName(),
                'country' => $instance->getOwnerInstitutions()->first()->getCountry()->getName(),
            );
        }
        return $response;
    }

}