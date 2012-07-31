<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Directory controller
 * 
 * @Route("/directory") 
 */
class DirectoryController extends BaseController
{
    /**
     * @Route("/", name="directory")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return array();
    }
    
    /**
     * @Route("/instances", name="directory_instances")
     * @Template()
     *
     * @return array
     */
    public function instancesAction()
    {
        return array();
    }
}