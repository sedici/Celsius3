<?php

namespace Celsius3\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;

/**
 * User controller.
 *
 * @Route("/admin/rest/orders")
 */
class AdminOrderRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("/{id}", name="admin_rest_order_get", options={"expose"=true})
     */
    public function getOrderAction($id)
    {
        $dm = $this->getDocumentManager();

        $order = $dm->getRepository('Celsius3CoreBundle:Order')
                ->find($id);

        if (!$order) {
            return $this->createNotFoundException('Order not found.');
        }

//        $request = $order->getRequest($this->getInstance());
//
//        if (!$request) {
//            return $this->createNotFoundException('Request not found.');
//        }
//
//        $catalogs = $this->get('celsius3_core.catalog_manager')->getAllCatalogs($this->getInstance())->toArray();
//
//        $searches = $this->get('celsius3_core.catalog_manager')->getSearches($request)->toArray();

        $view = $this->view(array(
                    'data' => $order
//                    'request' => $request,
//                    'catalogs' => $catalogs,
//                    'searches' => $searches,
                        ), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }
    
}
