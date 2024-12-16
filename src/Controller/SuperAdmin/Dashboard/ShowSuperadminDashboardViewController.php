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

namespace Celsius3\Controller\SuperAdmin\Dashboard;

use Celsius3\Controller\Api\UserController;
use Celsius3\Controller\BaseEntityController;
use Celsius3\Controller\BaseInstanceDependentController;
use Celsius3\Controller\BaseUserController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


/**
 * SuperAdminDashboard controller.
 *
 * @Route("/superadmin")
 */
final class ShowSuperadminDashboardViewController extends BaseUserController
{

    protected final function getTemplatePrefix(): string
    { return 'SuperAdmin/Dashboard/'; }


    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.createdAt',
            'defaultSortDirection' => 'asc',
        ];
    }

    /**
     * Lists all Order entities.
     *
     * @Route("/", name="superadministration")
     */
    public function index(): Response
    {
        return $this->render(
            (string) $this->templatePrefix . 'index.html.twig'
        );
    }
}
