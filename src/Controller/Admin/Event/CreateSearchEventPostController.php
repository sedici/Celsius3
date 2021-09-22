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

declare(strict_types=1);

namespace Celsius3\Controller\Admin\Event;

use Celsius3\CoreBundle\Controller\BaseInstanceDependentRestController;
use Celsius3\CoreBundle\Entity\Request;
use Celsius3\CoreBundle\Exception\Exception;
use Celsius3\Helper\LifecycleHelper;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;

final class CreateSearchEventPostController extends BaseInstanceDependentRestController
{
    private $requestRepository;
    private $lifecycleHelper;
    
    public function __construct(EntityManagerInterface $entityManager, LifecycleHelper $lifecycleHelper)
    {
        $this->requestRepository = $entityManager->getRepository(Request::class);
        $this->lifecycleHelper = $lifecycleHelper;
    }

    public function __invoke($request_id)
    {
        $request = $this->findRequest($request_id);

        $result = $this->lifecycleHelper->createSearchEvent($request, $this->getInstance());

        $view = $this->view($result, 200)->setFormat('json');

        $context = SerializationContext::create()->setGroups(['administration_order_show']);
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }

    private function findRequest($request_id)
    {
        $request = $this->requestRepository->find($request_id);

        if (!$request) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.request');
        }

        if (!$request->getOperator()) {
            $request->setOperator($this->getUser());
        }
        return $request;
    }
}
