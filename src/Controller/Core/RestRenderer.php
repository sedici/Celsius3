<?php

/*
 * Celsius3 - Rest renderer interface
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

namespace Celsius3\Controller\Core;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

class RestRenderer extends BaseRenderer
{

    public function render(... $args): Response
    {
        $view = $this->view(
            $this->getArg($args, 'data'),
            $this->getArgOrDefault($args, Response::HTTP_OK, 'statusCode'),
        )->setFormat('json');

        $groups = $this->getArgOrNull($args, 'serializerGroups');
        if ($groups) {
            $context = new Context();
            if (is_array($groups)) {
                $context->addGroups($groups);
            }
            if (is_string($groups)) {
                $context->addGroup($groups);
            }
            $view->setContext($context);
        }

        return $this->viewHandler->handle($view);
    }


    protected function view($data = null, ?int $statusCode = null, array $headers = []): View
    { return View::create($data, $statusCode, $headers); }


    public function index(
        array|string $serializerGroups = null
    ): Response {
        return $this->render(
            data: array_values($this->controller->listQuery()->getQuery()->execute()),
            serializerGroups: $serializerGroups
        );
    }


    public function show(
        string $id,
        array|string $serializerGroups = null
    ): Response {
        $query = $this->controller->findQuery($id);
        if (!$query) $this->controller->error('entity_not_found');
        return $this->render(data: $query, serializerGroups: $serializerGroups);
    }
}