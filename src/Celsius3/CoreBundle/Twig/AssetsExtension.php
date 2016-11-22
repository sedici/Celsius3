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

namespace Celsius3\CoreBundle\Twig;

class AssetsExtension extends \Twig_Extension
{
    private $assets_version_administration;
    private $assets_version_admin_order;
    private $assets_version_admin_base_user;
    private $assets_version_admin_institution;
    private $assets_version_user;
    private $assets_version_celsius3;

    public function __construct($administration, $admin_order, $admin_base_user, $admin_institution, $user, $celsius3)
    {
        $this->assets_version_administration = $administration;
        $this->assets_version_admin_order = $admin_order;
        $this->assets_version_admin_base_user = $admin_base_user;
        $this->assets_version_admin_institution = $admin_institution;
        $this->assets_version_user = $user;
        $this->assets_version_celsius3 = $celsius3;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('assets_version', array($this, 'getVersionForGroup')),
        );
    }

    public function getVersionForGroup($group)
    {
        $version = null;
        switch ($group) {
            case 'administration': $version = $this->assets_version_administration; break;
            case 'admin_order': $version = $this->assets_version_admin_order; break;
            case 'admin_base_user': $version = $this->assets_version_admin_base_user; break;
            case 'admin_institution': $version = $this->assets_version_admin_institution; break;
            case 'user': $version = $this->assets_version_user; break;
            case 'celsius3': $version = $this->assets_version_celsius3; break;
        }

        return (!is_null($version)) ? $version : '';
    }

    public function getName()
    {
        return 'celsius3_core.assets_extension';
    }
}
