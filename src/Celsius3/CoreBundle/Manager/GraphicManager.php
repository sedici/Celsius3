<?php

namespace Celsius3\CoreBundle\Manager;

use Doctrine\ODM\MongoDB\DocumentManager;

class GraphicManager
{

    private $dm;
    private $graphic_data = array(
        'usersPerInstance' => array(
            'repository' => 'Celsius3CoreBundle:BaseUser',
        )
    );

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function usersPerInstance()
    {
        $usersPerInstance = $this->dm
                ->getRepository($this->graphic_data['usersPerInstance']['repository'])
                ->findUsersPerInstance();

        $response = array();
        foreach ($usersPerInstance as $instance) {
            $response[] = array(
                
                'instance' => $this->dm->getRepository('Celsius3CoreBundle:Instance')
                        ->find((string)$instance['_id'])->getAbbreviation(),
                'value' => $instance['value'],
            );
        }
        return $response;
    }

}