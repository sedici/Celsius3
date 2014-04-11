<?php

namespace Celsius3\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Celsius3\CoreBundle\Document\CatalogSearch;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

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

        $view = $this->view(array_values($searches), 200)
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

        $view = $this->view($search, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @Post("/{request_id}", name="admin_rest_catalogsearch_create", options={"expose"=true})
     */
    public function createCatalogSearchAction($request_id, HttpRequest $httpRequest)
    {
        $dm = $this->getDocumentManager();

        if (!$request = $dm->getRepository('Celsius3CoreBundle:Request')->find($request_id)) {
            return $this->createNotFoundException('Request not found.');
        }

        if (!$catalog = $dm->getRepository('Celsius3CoreBundle:Catalog')
                ->find($this->getRequest()->request->get('catalog[id]', null, true))) {
            return $this->createNotFoundException('Catalog not found.');
        }

        if (!$this->getRequest()->request->has('result')) {
            return $this->createNotFoundException('Result not found.');
        }

        $result = $httpRequest->request->get('result');
        
        $request->setOperator($this->getUser());

        $document = $this->get('celsius3_core.catalog_manager')->createSearch($catalog, $request, $this->getUser(), $result);

        $view = $this->view($document, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

}
