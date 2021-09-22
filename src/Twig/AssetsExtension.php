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

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AssetsExtension extends AbstractExtension
{
    private $assetsVersionAdministration;
    private $assetsVersionAdminOrder;
    private $assetsVersionAdminBaseUser;
    private $assetsVersionAdminInstitution;
    private $assetsVersionUser;
    private $assetsVersionCelsius3;

    public function __construct($administration, $admin_order, $admin_base_user, $admin_institution, $user, $celsius3)
    {
        $this->assetsVersionAdministration = $administration;
        $this->assetsVersionAdminOrder = $admin_order;
        $this->assetsVersionAdminBaseUser = $admin_base_user;
        $this->assetsVersionAdminInstitution = $admin_institution;
        $this->assetsVersionUser = $user;
        $this->assetsVersionCelsius3 = $celsius3;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('assets_version', [$this, 'getVersionForGroup']),
        ];
    }

    public function getVersionForGroup($group): string
    {
        $version = null;

        switch ($group) {
            case 'administration':
                $version = $this->assetsVersionAdministration;
                break;
            case 'admin_order':
                $version = $this->assetsVersionAdminOrder;
                break;
            case 'admin_base_user':
                $version = $this->assetsVersionAdminBaseUser;
                break;
            case 'admin_institution':
                $version = $this->assetsVersionAdminInstitution;
                break;
            case 'user':
                $version = $this->assetsVersionUser;
                break;
            case 'celsius3':
                $version = $this->assetsVersionCelsius3;
                break;
        }

        return $version ?? '';
    }

    public function getName(): string
    {
        return 'celsius3_core.assets_extension';
    }
}
