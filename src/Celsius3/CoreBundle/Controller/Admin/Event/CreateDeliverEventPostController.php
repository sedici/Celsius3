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

namespace Celsius3\CoreBundle\Controller\Admin\Event;

use Celsius3\CoreBundle\Entity\Request;
use Celsius3\CoreBundle\Exception\Exception;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Doctrine\ORM\EntityManagerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\Serializer\SerializationContext;

final class CreateDeliverEventPostController
{
    private $entityManager;
    private $lifecycleHelper;

    /**
     * @DI\InjectParams({
     *     "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     *     "lifecycleHelper" = @DI\Inject("celsius3_core.lifecycle_helper")
     * })
     */
    public function __construct(EntityManagerInterface $entityManager, LifecycleHelper $lifecycleHelper)
    {
        $this->entityManager = $entityManager;
        $this->lifecycleHelper = $lifecycleHelper;
    }

    public function __invoke($request_id)
    {
        $request = $this->findRequest($request_id);

        $result = $this->lifecycleHelper->createDeliverEvent($request, $this->getInstance());

        $view = $this->view($result, 200)->setFormat('json');

        $context = SerializationContext::create()->setGroups(['administration_order_show']);
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }

    private function findRequest($request_id)
    {
        $request = $this->entityManager->getRepository(Request::class)->find($request_id);

        if (!$request) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.request');
        }

        if (!$request->getOperator()) {
            $request->setOperator($this->getUser());
        }
        return $request;
    }
}
