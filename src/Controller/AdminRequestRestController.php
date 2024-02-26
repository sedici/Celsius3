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

namespace Celsius3\Controller;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Celsius3\Exception\Exception;
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
    public function getRequests()
    {
        $em = $this->getDoctrine()->getManager();

        $requests = $em->getRepository(\Celsius3\Entity\Request::class)
                ->findBy(array('instance' => $this->getInstance()->getId()));

        $view = $this->view(array_values($requests), 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     *
     * @Get("/{order_id}", name="admin_rest_request_get", options={"expose"=true})
     */
    public function getRequest($order_id)
    {
        $em = $this->getDoctrine()->getManager();

        $request = $em->getRepository(\Celsius3\Entity\Request::class)
                ->findOneBy(array(
            'order' => $order_id,
            'instance' => $this->getInstance()->getId(),
        ));

        if (!$request) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.request');
        }

        $view = $this->view($request, 200)->setFormat('json');

   //     $context = new Context();

      //  $context->addGroup('administration_order_show');
  //      $view->setContext($context);

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     *
     * @Post("/reenable_download", name="admin_rest_request_reenable_download", options={"expose"=true})
     */
    public function reenableDownload(Request $req)
    {
        $request_id = $req->request->get('request_id');
        $manager = $this->getDoctrine()->getManager();
        $request = $this->getDoctrine()->getRepository(\Celsius3\Entity\Request::class)->find($request_id);

        foreach ($request->getFiles() as $file) {
            if ($file->getEnabled()) {
                $file->setDownloaded(false);
                $manager->persist($file);
            }
        }

        $manager->flush();

        $view = $this->view(['reenabled' => true], 200)->setFormat('json');

        return $this->handleView($view);
    }
}
