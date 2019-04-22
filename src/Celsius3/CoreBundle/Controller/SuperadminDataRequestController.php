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

use Celsius3\CoreBundle\Entity\DataRequest;
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
    public function indexAction(Request $request)
    {
        $qb = $this->getDoctrine()->getManager()
            ->getRepository(DataRequest::class)
            ->createQueryBuilder('e');

        $query = $qb->where('e.exported = :exported')
            ->orWhere('e.downloaded = :downloaded')
            ->setParameter('exported', false)
            ->setParameter('downloaded', false);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */, $this->getSortDefaults());

        return $this->render('Celsius3CoreBundle:SuperadminDataRequests:index.html.twig', array('pagination' => $pagination));
    }

    /**
     * @param Request $request
     * @param DataRequest $dataRequest
     *
     * @Route("/{id}/export", name="superadmin_data_request_export")
     */
    public function exportAction(Request $request, DataRequest $dataRequest)
    {
        $em = $this->getDoctrine()->getManager();

        $process = new Process('php ../app/console celsius3:export-data ' . $dataRequest->getId());
        $process->run();

        $em->persist($dataRequest->setExported(true));
        $em->flush();

        return $this->redirectToRoute('superadmin_data_request_index');

    }


}
