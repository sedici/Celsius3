<?php

namespace Celsius3\ApiBundle\Security;

use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius3\CoreBundle\Exception\NotFoundException;

class InstanceProvider
{
    
    private $dm;
    
    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }
    
    
    public function loadByUrl($url)
    {
        $instance = $this->dm->getRepository('Celsius3CoreBundle:Instance')
                ->findOneBy(array('url' => $url));
        
        if (!$instance) {
            throw new NotFoundException('Instance not found.');
        }
        
        return $instance;
    }

}