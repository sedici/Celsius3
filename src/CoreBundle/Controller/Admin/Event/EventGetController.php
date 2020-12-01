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

use Celsius3\CoreBundle\Controller\BaseInstanceDependentRestController;
use Celsius3\CoreBundle\Exception\Exception;
use Doctrine\ORM\EntityManagerInterface;
use JMS\DiExtraBundle\Annotation as DI;

final class EventGetController extends BaseInstanceDependentRestController
{
    private $entityManager;

    /**
     * @DI\InjectParams({
     *     "entityManager" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke($id)
    {
        $event = $this->findEvent($id);

        $view = $this->view($event, 200)->setFormat('json');

        return $this->handleView($view);
    }

    private function findEvent($id)
    {
        $event = $this->entityManager->getRepository('Celsius3CoreBundle:Event')
            ->find($id);

        if (!$event) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.event');
        }

        return $event;
    }
}
