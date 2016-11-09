<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
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
use FOS\RestBundle\Controller\Annotations\Post;
use JMS\Serializer\SerializationContext;
use Celsius3\CoreBundle\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

/**
 * User controller.
 *
 * @Route("/admin/rest/requests")
 */
class AdminRequestRestController extends BaseInstanceDependentRestController
{
    /**
     * GET Route annotation.
     *
     * @Get("/", name="admin_rest_request", options={"expose"=true})
     */
    public function getRequestsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $requests = $em->getRepository('Celsius3CoreBundle:Request')
                ->findBy(array('instance' => $this->getInstance()->getId()));

        $view = $this->view(array_values($requests), 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     *
     * @Get("/{order_id}", name="admin_rest_request_get", options={"expose"=true})
     */
    public function getRequestAction($order_id)
    {
        $context = SerializationContext::create()->setGroups(array('administration_order_show'));

        $em = $this->getDoctrine()->getManager();

        $request = $em->getRepository('Celsius3CoreBundle:Request')
                ->findOneBy(array(
            'order' => $order_id,
            'instance' => $this->getInstance()->getId(),
        ));

        if (!$request) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.request');
        }

        $view = $this->view($request, 200)->setFormat('json');
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     *
     * @Post("/reenable_download", name="admin_rest_request_reenable_download", options={"expose"=true})
     */
    public function reenableDownloadAction(Request $req)
    {
        $request_id = $req->request->get('request_id');
        $manager = $this->getDoctrine()->getManager();
        $request = $this->getDoctrine()->getRepository('Celsius3CoreBundle:Request')->find($request_id);

        $array = [];
        foreach ($request->getFiles() as $file) {
            $array[] = $file->getId();
            if ($file->getEnabled()) {
                $file->setIsDownloaded(false);
                $manager->persist($file);
            }
        }

        $manager->flush();

        $view = $this->view(['reenabled' => true], 200)->setFormat('json');

        return $this->handleView($view);
    }
}
