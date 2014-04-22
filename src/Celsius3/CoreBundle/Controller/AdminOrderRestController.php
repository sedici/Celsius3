<?php

namespace Celsius3\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\Request;

/**
 * User controller.
 *
 * @Route("/admin/rest/orders")
 */
class AdminOrderRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("/", name="admin_rest_order", options={"expose"=true})
     */
    public function getOrdersAction()
    {
        $dm = $this->getDocumentManager();

        $orders = $dm->getRepository('Celsius3CoreBundle:Order')
                ->findBy(array('instance.id' => $this->getInstance()->getId(),));

        $view = $this->view(array_values($orders), 200)
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
