<?php

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
     * @Route("/graphic", name="superadmin_graphic", options={"expose"=true})
     * @Method("post")
     * @Template()
     */
    public function graphicAction()
    {
        $graphic = $this->getRequest()->request->get('graphic');
        if (!$graphic) {
            return $this->createNotFoundException();
        }

        return new Response(json_encode($this->get('celsius3_core.graphic_manager')->$graphic()));
    }

}
