<?php

namespace Celsius3\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;

/**
 * User controller.
 *
 * @Route("/user/rest/orders")
 */
class UserOrderRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("", name="user_rest_order", options={"expose"=true})
     */
    public function getOrdersAction(Request $request)
    {
        $withRequest = $request->query->get('withRequest', false);
        
        $states = explode(',', $request->query->get('state', ''));

        $orders = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:Order')
                ->findForInstance($this->getInstance(), null, $states, $this->getUser());

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($orders, $this->get('request')->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */)->getItems();

        if ($withRequest) {
            $requests = $this->getDocumentManager()
                    ->getRepository('Celsius3CoreBundle:Request')
                    ->createQueryBuilder()
                    ->field('order.id')->in(array_map(function ($order) {
                                return $order->getId();
                            }, $pagination))
                    ->getQuery()
                    ->execute()
                    ->toArray();

            $response = array(
                'orders' => array_values($pagination),
                'requests' => array_column(array_map(function($request) {
                            return array(
                                'id' => $request->getOrder()->getId(),
                                'request' => $request,
                            );
                        }, $requests), 'request', 'id'),
            );

            $view = $this->view($response, 200)
                    ->setFormat('json');
        } else {
            $view = $this->view(array_values($pagination), 200)
                    ->setFormat('json');
        }

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