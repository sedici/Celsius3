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

use Celsius3\CoreBundle\Entity\Institution;

class InstitutionExtension extends \Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_country', array($this, 'getCountry')),
            new \Twig_SimpleFunction('get_city', array($this, 'getCity')),
            new \Twig_SimpleFunction('print_institutions', array($this, 'printInstitutions'))
        );
    }

    public function getCountry(Institution $institution = null)
    {
        return $institution ? $institution->getCountry() ? $institution->getCountry() : '' : '';
    }

    public function getCity(Institution $institution = null)
    {
        return $institution ? $institution->getCity() ? $institution->getCity() : '' : '';
    }

    public function getName()
    {
        return 'celsius3_core.institution_extension';
    }

    public function printInstitutions(Institution $institution = null)
    {
        $txt = '';
        if (!is_null($institution)) {
            if (!is_null($institution->getParent())) {
                $txt .= $this->printInstitutions($institution->getParent()) . ' - ' . $institution->getName();
            } else {
                $txt .= $institution->getName();
            }
        }
        return $txt;
    }

}
