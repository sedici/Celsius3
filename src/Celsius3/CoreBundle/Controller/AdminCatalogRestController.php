<?php

namespace Celsius3\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;

/**
 * User controller.
 *
 * @Route("/admin/rest/catalogs")
 */
class AdminCatalogRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("", name="admin_rest_catalog", options={"expose"=true})
     */
    public function getCatalogsAction()
    {
        $catalogs = $this->get('celsius3_core.catalog_manager')->getAllCatalogs($this->getInstance())->toArray();

        $view = $this->view(array_values($catalogs), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{id}", name="admin_rest_catalog_get", options={"expose"=true})
     */
    public function getCatalogAction($id)
    {
        $dm = $this->getDocumentManager();

        $catalog = $dm->getRepository('Celsius3CoreBundle:Catalog')
                ->find($id);

        if (!$catalog) {
            return $this->createNotFoundException('Catalog not found.');
        }

        $view = $this->view($catalog, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/results/{title}", name="admin_rest_catalog_results", options={"expose"=true})
     */
    public function getCatalogResultsAction($title)
    {
        $catalogs = $this->get('celsius3_core.catalog_manager')->getAllCatalogs($this->getInstance());
        $results = $this->get('celsius3_core.catalog_manager')
                ->getCatalogResults($catalogs, $title)
                ->toArray();

        $view = $this->view(array_values($results), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

}
