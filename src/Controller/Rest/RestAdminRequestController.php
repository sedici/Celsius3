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

namespace Celsius3\Controller\Rest;

use Celsius3\Controller\Core\EntityController;
use Celsius3\Entity\Order;
use Celsius3\Entity\Request as CelsiusRequest;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[
    Route(
        '/rest/v1/admin/requests',
        options: ['expose' => true]
    ),
    IsGranted('ROLE_ADMIN')
]
class RestAdminRequestController extends EntityController
{

    public function initialize(): void
    {
        $this->setEntity(CelsiusRequest::class);
        parent::initialize();
        $this->setInstanceDependent(true);
    }

    #[Route(
        '/',
        name: 'admin_rest_request',
        options: ['expose' => true],
        methods: ['GET']
    )]
    public function getRequests(): Response
    {
        return $this->restRenderer->render(
            $this->repository->findBy(
                [ 'instance'=>$this->instance->getId() ]
            )
        );
    }

    #[Route(
        '/{id}',
        name: 'admin_rest_request_get',
        options: ['expose' => true],
        methods: ['GET']
    )]
    public function getRequest(string $id)
    { return $this->restRenderer->show($id, 'administration_order_show'); }


    #[Route(
        '/from_order/{id}',
        name: 'admin_rest_request_from_order',
        options: ['expose' => true],
        methods: ['GET']
    )]
    public function getRequestFromOrder(string $id): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);
        if (!$order) $this->error('not_found', Order::class);

        $request = $this->repository->findOneBy([
            'instance' => $this->instance,
            'order' => $order
        ]);

        if (!$request) $this->error('not_found');

        return $this->restRenderer->render($request, serializerGroups: 'administration_order_show');
    }


    #[Route(
        '/reenable_download',
        name: 'admin_rest_request_reenable_download',
        options: ['expose' => true],
        methods: ['POST']
    )]
    public function reenableDownload(): Response
    {
        $requestId = $this->requestStack->getCurrentRequest()->get('request_id');
        $cRequest = $this->findQuery($requestId);

        foreach ($cRequest->getFiles() as $file) {
            if ($file->getEnabled()) {
                $file->setDownloaded(false);
                ${$this}->entityManager->persist($file);
            }
        }

        $this->entityManager->flush();

        return $this->restRenderer->render([ 'reenambled' => true ]);
    }
}
