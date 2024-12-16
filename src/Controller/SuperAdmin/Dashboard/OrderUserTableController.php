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

use Celsius3\Controller\BaseEntityController;
use Celsius3\Controller\OrderController;
use Celsius3\Manager\StatisticManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Order controller.
 *
 * @Route("/superadmin/orderusertable")
 */
final class OrderUserTableController extends OrderController
{

    private StatisticManager $statsManager;

    public function __construct(
        StatisticManager $statsManager,
        ... $args
    ) {
        parent::__construct(... $args);
        $this->statsManager = $statsManager;
    }

    protected final function getTemplatePrefix(): string
    { return 'SuperAdmin/Dashboard/'; }


    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.updatedAt',
            'defaultSortDirection' => 'asc',
        ];
    }


    /**
     * Lists all Order entities.
     *
     * @Route("/", name="superadmin_orderusertable")
     */
    public function index()
    {
        $request = $this->requestStack->getMainRequest();

        if (!$request->isXmlHttpRequest()) {
            return $this->createNotFoundException();
        }

        return new Response(
            json_encode(
                $this->statsManager->getOrderUserTableData()
            )
        );
    }
}
