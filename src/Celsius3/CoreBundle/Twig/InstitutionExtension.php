<?php
/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

use Celsius3\CoreBundle\Document\Institution;

class InstitutionExtension extends \Twig_Extension
{

    public function getFunctions()
    {
        return array(
            'full_name' => new \Twig_Function_Method($this, 'fullName'),
            'get_country' => new \Twig_Function_Method($this, 'getCountry'),
            'get_city' => new \Twig_Function_Method($this, 'getCity'),
        );
    }

    public function fullName(Institution $institution = null)
    {
        return $institution ? $institution->getFullName() : '';
    }

    public function getCountry(Institution $institution = null)
    {
        return $institution ? $institution->getCountry() ? $institution->getCountry() : ''  : '';
    }

    public function getCity(Institution $institution = null)
    {
        return $institution ? $institution->getCity() ? $institution->getCity() : ''  : '';
    }

    public function getName()
    {
        return 'celsius3_core.institution_extension';
    }

}