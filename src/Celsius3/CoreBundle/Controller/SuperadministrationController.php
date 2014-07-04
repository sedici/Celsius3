<?php

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * @Route("/orderusertable", name="superadmin_orderusertable", options={"expose"=true})
     * @Template()
     */
    public function orderUserTableAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            return $this->createNotFoundException();
        }

        return new Response(json_encode($this->get('celsius3_core.statistic_manager')->getOrderUserTableData()));
    }

}
