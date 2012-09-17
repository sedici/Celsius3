<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Directory controller
 * 
 * @Route("") 
 */
class DirectoryController extends BaseController
{
    
    /**
     * @Route("/directory/", name="directory")
     * @Route("/", name="index")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return array(
           'lastNews' => $this->getDocumentManager()->getRepository('CelsiusCelsius3Bundle:News')->findLastNewsDirectory(),
        );
    }
    
    /**
     * @Route("/directory/instances", name="directory_instances")
     * @Template()
     *
     * @return array
     */
    public function instancesAction()
    {
        $instances = $this->getDocumentManager()
                ->getRepository('CelsiusCelsius3Bundle:Instance')
                ->createQueryBuilder()
                ->field('enabled')->equals(true)
                ->getQuery()
                ->execute();
        
        return array(
            'instances' => $instances,
        );
    }
}