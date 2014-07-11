<?php

namespace Celsius3\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
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
     * @Get("", name="admin_rest_order", options={"expose"=true})
     */
    public function getOrdersAction(Request $request)
    {
        if ($request->query->get('type', null) === 'mine') {
            $user = $this->getUser();
        } else {
            $user = null;
        }

        $state = $request->query->get('state', null);

        $orders = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:Order')
                ->findForInstance($this->getInstance(), $user, $state);
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($orders, $this->get('request')->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */)->getItems();

        $view = $this->view(array_values($pagination), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/count", name="admin_rest_order_count_get", options={"expose"=true})
     */
    public function getOrderCountAction(Request $request)
    {
        if ($request->query->get('type', null) === 'mine') {
            $user = $this->getUser();
        } else {
            $user = null;
        }

        $orderCount = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:State')
                ->countOrders($this->getInstance(), $user);

        $view = $this->view($orderCount, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

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

        $view = $this->view($order, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }
}