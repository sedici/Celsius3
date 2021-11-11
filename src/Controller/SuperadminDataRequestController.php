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

use Celsius3\CoreBundle\Entity\DataRequest;
use Celsius3\CoreBundle\Entity\UsersDataRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Process\Process;

/**
 * Data requests list controller.
 *
 * @Route("/superadmin/data_request")
 */
class SuperadminDataRequestController extends BaseController
{

    /**
     * Lists all data requests.
     *
     * @Route("/", name="superadmin_data_request_index")
     */
    public function index(Request $request)
    {
        $qb = $this->getDoctrine()->getManager()
            ->getRepository(DataRequest::class)
            ->createQueryBuilder('e');

        $query = $qb->where('e.visible = :visible')
            ->setParameter('visible', true);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */, $this->getSortDefaults());

        return $this->render('Superadmin/DataRequests/index.html.twig', array('pagination' => $pagination));
    }

    /**
     * @Route("/{id}/export_orders", name="superadmin_orders_data_request_export")
     */
    public function exportOrders(Request $request, DataRequest $dataRequest)
    {
        $em = $this->getDoctrine()->getManager();

        $process = new Process('php ../bin/console --env=prod celsius3:export:orders-data-requests ' . $dataRequest->getId());
        $process->run();

        $em->persist($dataRequest->setExported(true));
        $em->flush();

        return $this->redirectToRoute('superadmin_data_request_index');
    }

    /**
     * @Route("/{id}/export_users", name="superadmin_users_data_request_export")
     */
    public function exportUsers(Request $request, DataRequest $dataRequest)
    {
        $em = $this->getDoctrine()->getManager();

        $process = new Process('php ../bin/console --env=prod celsius3:export:users-data-requests ' . $dataRequest->getId());
        $process->run();

        $em->persist($dataRequest->setExported(true));
        $em->flush();

        return $this->redirectToRoute('superadmin_data_request_index');
    }

    /**
     * @Route("/{id}/annul", name="superadmin_data_request_annul")
     */
    public function annul(Request $request, DataRequest $dataRequest) {
        $em = $this->getDoctrine()->getManager();

        $em->persist($dataRequest->setVisible(false));
        $em->flush();

        return $this->redirectToRoute('superadmin_data_request_index');
    }

}
