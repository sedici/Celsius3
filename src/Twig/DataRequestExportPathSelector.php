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

namespace Celsius3\Twig;

use Celsius3\Entity\OrdersDataRequest;
use Celsius3\Entity\UsersDataRequest;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use function get_class;

final class DataRequestExportPathSelector extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_data_request_export_path', [$this, 'getDataRequestExportPath']),
        ];
    }

    public function getDataRequestExportPath($object): string
    {
        $path = '';

        switch (get_class($object)) {
            case OrdersDataRequest::class:
                $path = 'superadmin_orders_data_request_export';
                break;
            case UsersDataRequest::class:
                $path = 'superadmin_users_data_request_export';
                break;
        }

        return $path;
    }
}
