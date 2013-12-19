<?php

namespace Celsius3\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;

/**
 * User controller.
 *
 * @Route("/admin/rest/order")
 */
class AdminOrderRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("/{id}", name="admin_rest_order_show", options={"expose"=true})
     */
    public function showAction($id)
    {
        $dm = $this->getDocumentManager();

        $order = $dm->getRepository('Celsius3CoreBundle:Order')
                ->find($id);

        if (!$order) {
            return $this->createNotFoundException('Order not found.');
        }

        $request = $order->getRequest($this->getInstance());

        if (!$request) {
            return $this->createNotFoundException('Request not found.');
        }

        $view = $this->view(array(
                    'order' => $order,
                    'request' => $request,
                        ), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

}
