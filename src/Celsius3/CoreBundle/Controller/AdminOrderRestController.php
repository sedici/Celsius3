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

    /**
     * @Post("/{id}/event/{event}", name="admin_rest_order_event", options={"expose"=true})
     */
    public function registerEventAction($id, $event, Request $request)
    {
        $dm = $this->getDocumentManager();

        $order = $dm->getRepository('Celsius3CoreBundle:Order')
                ->find($id);

        if (!$order) {
            throw $this->createNotFoundException('Unable to find Order.');
        }

        $result = $this->get('celsius3_core.lifecycle_helper')->createEvent($event, $order->getRequest($this->getInstance()));

        $view = $this->view($result, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

}
