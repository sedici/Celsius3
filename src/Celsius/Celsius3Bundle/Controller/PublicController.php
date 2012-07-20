<?php

namespace Celsius\Celsius3Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Public controller
 * 
 * @Route("/{url}/public") 
 */
class PublicController
{
    /**
     * @Route("/", name="public_index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
    
    
    /**
     * @Route("/information", name="public_information")
     * @Template()
     */
    public function informationAction()
    {
        return array();
    }
    
    /**
     * @Route("/news", name="public_news")
     * @Template()
     */
    public function newsAction()
    {
        return array();
    }
    
    /**
     * @Route("/statistics", name="public_statistics")
     * @Template()
     */
    public function statisticsAction()
    {
        return array();
    }
}
