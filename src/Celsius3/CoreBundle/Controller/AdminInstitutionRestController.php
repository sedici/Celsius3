<?php

namespace Celsius3\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

/**
 * User controller.
 *
 * @Route("/admin/rest/institution")
 */
class AdminInstitutionRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("/{country_id}", name="admin_rest_institution", options={"expose"=true})
     */
    public function getInstitutionsAction($country_id)
    {
        $dm = $this->getDocumentManager();

        $countries = $dm->getRepository('Celsius3CoreBundle:Institution')
                ->findForInstanceAndGlobal($this->getInstance(), $this->getDirectory(), $country_id);

        $view = $this->view(array_values($countries), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{id}", name="admin_rest_institution_get", options={"expose"=true})
     */
    public function getInstitutionAction($id)
    {
        $dm = $this->getDocumentManager();

        $institution = $dm->getRepository('Celsius3CoreBundle:Institution')
                ->find($id);

        if (!$institution) {
            return $this->createNotFoundException('Institution not found.');
        }

        $view = $this->view($institution, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

}
