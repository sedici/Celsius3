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

use Celsius3\Controller\Core\EntityController;
use Celsius3\Entity\Request as CelsiusRequest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Celsius3\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * User controller.
 *
 * @Route("/rest/v1/admin/requests")
 */
class RestAdminRequestController extends EntityController
{

    public function initialize(): void
    {
        $this->setEntity(CelsiusRequest::class);
        parent::initialize();
        $this->setInstanceDependent(true);
    }

    /**
     * GET Route annotation.
     * @Get("/", name="admin_rest_request", options={"expose"=true})
     */
    public function getRequests(): Response
    {
        return $this->restRenderer->render(
            $this->repository->findBy(
                [ 'instance'=>$this->instance->getId() ]
            )
        );
    }


    /**
     * GET Route annotation.
     * @Get("/{order_id}", name="admin_rest_request_get", options={"expose"=true})
     */
    public function getRequest(string $id)
    { return $this->restRenderer->render($this->findQuery($id)); }


    /**
     * GET Route annotation.
     * @Post("/reenable_download", name="admin_rest_request_reenable_download", options={"expose"=true})
     */
    public function reenableDownload(): Response
    {
        $requestId = $this->requestStack->getCurrentRequest()->get('request_id');
        $cRequest = $this->findQuery($requestId);

        foreach ($cRequest->getFiles() as $file) {
            if ($file->getEnabled()) {
                $file->setDownloaded(false);
                $$this->entityManager->persist($file);
            }
        }

        $this->entityManager->flush();

        return $this->restRenderer->render([ 'reenambled' => true ]);
    }
}
