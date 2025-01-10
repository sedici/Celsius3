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

use Celsius3\Entity\DataRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\DataRequestController;

/**
 * Data requests list controller.
 *
 * @Route("/superadmin/data_request")
 */
class SuperadminDataRequestController extends DataRequestController
{

    final protected function getTemplatePrefix(): string
    { return 'Superadmin/DataRequests/'; }


    /**
     * Lists all data requests.
     *
     * @Route("/", name="superadmin_data_request_index")
     */
    public function index(): Response
    {
        $qb = $this->managerRegistry->getManager()
            ->getRepository($this->entityClassName)
            ->createQueryBuilder('e');

        $query = $qb
            ->where('e.visible = :visible')
            ->setParameter('visible', true);

        $request = $this->requestStack->getCurrentRequest();

        $pagination = $this->paginator->paginate(
            $query,
            intval($request->query->get('page', 1)),
            $this->getResultsPerPage(),
            $this->sortDefaults
        );

        return $this->render(
            (string) $this->templatePrefix . 'index.html.twig',
            [ 'pagination' => $pagination ]
        );
    }


    /**
     * @Route("/{id}/export_orders", name="superadmin_orders_data_request_export")
     */
    public function exportOrders(DataRequest $dataRequest): RedirectResponse
    {
        $process = new Process([
            'php ../bin/console --env=prod celsius3:export:orders-data-requests ' . $dataRequest->getId()
        ]);
        $process->run();

        $this->persistEntity(
            $dataRequest->setExported(true)
        );

        return $this->redirectToRoute('superadmin_data_request_index');
    }


    /**
     * @Route("/{id}/export_users", name="superadmin_users_data_request_export")
     */
    public function exportUsers(DataRequest $dataRequest): RedirectResponse
    {
        $process = new Process([
            'php ../bin/console --env=prod celsius3:export:users-data-requests ' . $dataRequest->getId()
        ]);
        $process->run();

        $this->persistEntity($dataRequest->setExported(true));

        return $this->redirectToRoute('superadmin_data_request_index');
    }

    /**
     * @Route("/{id}/annul", name="superadmin_data_request_annul")
     */
    public function annul(DataRequest $dataRequest): RedirectResponse
    {
        $this->persistEntity($dataRequest->setVisible(false));

        return $this->redirectToRoute('superadmin_data_request_index');
    }
}
