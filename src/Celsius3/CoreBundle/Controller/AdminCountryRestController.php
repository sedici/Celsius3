<?php

namespace Celsius3\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

/**
 * User controller.
 *
 * @Route("/admin/rest/country")
 */
class AdminCountryRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("/", name="admin_rest_country", options={"expose"=true})
     */
    public function getCountriesAction()
    {
        $dm = $this->getDocumentManager();

        $countries = $dm->getRepository('Celsius3CoreBundle:Country')
                ->findForInstanceAndGlobal($this->getInstance(), $this->getDirectory());

        $view = $this->view(array_values($countries), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{id}", name="admin_rest_country_get", options={"expose"=true})
     */
    public function getCountryAction($id)
    {
        $dm = $this->getDocumentManager();

        $country = $dm->getRepository('Celsius3CoreBundle:Country')
                ->find($id);

        if (!$country) {
            return $this->createNotFoundException('Country not found.');
        }

        $view = $this->view($country, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

}
