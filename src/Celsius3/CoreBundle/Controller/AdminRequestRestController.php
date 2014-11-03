<?php
/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;

/**
 * User controller.
 *
 * @Route("/admin/rest/requests")
 */
class AdminRequestRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("/", name="admin_rest_request", options={"expose"=true})
     */
    public function getRequestsAction()
    {
        $em = $this->getDocumentManager();

        $requests = $em->getRepository('Celsius3CoreBundle:Request')
                ->findBy(array('instance.id' => $this->getInstance()->getId(),));

        $view = $this->view(array_values($requests), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{order_id}", name="admin_rest_request_get", options={"expose"=true})
     */
    public function getRequestAction($order_id)
    {
        $em = $this->getDocumentManager();

        $request = $em->getRepository('Celsius3CoreBundle:Request')
                ->findOneBy(array(
            'order.id' => $order_id,
            'instance.id' => $this->getInstance()->getId(),
        ));

        if (!$request) {
            return $this->createNotFoundException('Request not found.');
        }

        $view = $this->view($request, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

}