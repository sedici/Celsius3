<?php

namespace Celsius3\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;

/**
 * User controller.
 *
 * @Route("/orders")
 */
class OrderController extends BaseController
{

    /**
     * GET Route annotation.
     * @Get("/{user_id}")
     */
    public function ordersAction($user_id)
    {
        $dm = $this->getDocumentManager();

        $user = $dm->getRepository('Celsius3CoreBundle:BaseUser')
                ->find($user_id);

        if (!$user) {
            return $this->createNotFoundException('User not found.');
        }

        $orders = $dm->getRepository('Celsius3CoreBundle:Order')
                ->findBy(array(
                    'owner.id' => $user->getId(),
                    'instance.id' => $this->getInstance()->getId(),
                ))
                ->toArray();

        $view = $this->view($orders, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }
    
    /**
     * GET Route annotation.
     * @Get("/state/{state}")
     */
    public function ordersByStateAction($state)
    {
        $dm = $this->getDocumentManager();

        $orders = $dm->getRepository('Celsius3CoreBundle:Order')
                ->findByStateType($state, null, $this->getInstance())
                ->toArray();

        $view = $this->view($orders, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{user_id}/state/{state}")
     */
    public function ordersByUserAndStateAction($user_id, $state)
    {
        $dm = $this->getDocumentManager();

        $user = $dm->getRepository('Celsius3CoreBundle:BaseUser')
                ->find($user_id);

        if (!$user) {
            return $this->createNotFoundException('User not found.');
        }

        $orders = $dm->getRepository('Celsius3CoreBundle:Order')
                ->findByStateType($state, $user, $this->getInstance())
                ->toArray();

        $view = $this->view($orders, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

}