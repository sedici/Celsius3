<?php

namespace Celsius3\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;

/**
 * User controller.
 *
 * @Route("/admin/rest/city")
 */
class AdminCityRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("/{country_id}", name="admin_rest_city", options={"expose"=true})
     */
    public function getCitiesAction($country_id)
    {
        $dm = $this->getDocumentManager();

        $countries = $dm->getRepository('Celsius3CoreBundle:City')
                ->findForInstanceAndGlobal($this->getInstance(), $this->getDirectory(), $country_id);

        $view = $this->view(array_values($countries), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{id}", name="admin_rest_city_get", options={"expose"=true})
     */
    public function getCityAction($id)
    {
        $dm = $this->getDocumentManager();

        $institution = $dm->getRepository('Celsius3CoreBundle:City')
                ->find($id);

        if (!$institution) {
            return $this->createNotFoundException('City not found.');
        }

        $view = $this->view($institution, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

}
