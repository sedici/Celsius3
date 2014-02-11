<?php

namespace Celsius3\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;

/**
 * User controller.
 *
 * @Route("/admin/rest/catalogsearch")
 */
class AdminCatalogSearchRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("/{request_id}", name="admin_rest_catalogsearch", options={"expose"=true})
     */
    public function getCatalogSearchesAction($request_id)
    {
        $dm = $this->getDocumentManager();

        $request = $dm->getRepository('Celsius3CoreBundle:Request')
                ->find($request_id);

        if (!$request) {
            return $this->createNotFoundException('Request not found.');
        }

        $searches = $this->get('celsius3_core.catalog_manager')->getSearches($request)->toArray();

        $view = $this->view(array(
                    'data' => $searches,
                        ), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }
    
    /**
     * GET Route annotation.
     * @Get("/{id}", name="admin_rest_catalogsearch_get", options={"expose"=true})
     */
    public function getCatalogSearchAction($id)
    {
        $dm = $this->getDocumentManager();

        $search = $dm->getRepository('Celsius3CoreBundle:CatalogSearch')
                ->find($id);

        if (!$search) {
            return $this->createNotFoundException('Catalog search not found.');
        }

        $view = $this->view(array(
                    'data' => $search,
                        ), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

}
