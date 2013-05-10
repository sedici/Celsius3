<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Administration controller
 * 
 * @Route("/superadmin") 
 */
class SuperadministrationController extends BaseController
{

    /**
     * @Route("/", name="superadministration")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return array();
    }
    
    /**
     * @Route("/ajax", name="superadmin_ajax")
     */
    public function ajaxAction()
    {
        return $this->ajax();
    }

}