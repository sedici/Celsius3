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

use Celsius3\CoreBundle\Entity\MaterialType;

class MaterialTypeExtension extends \Twig_Extension
{
    private $materials = array(
        'BookType' => 'book',
        'CongressType' => 'congress',
        'JournalType' => 'journal',
        'PatentType' => 'patent',
        'ThesisType' => 'thesis',
    );

    private function getClassName(MaterialType $material)
    {
        $class = explode('\\', get_class($material));

        return end($class);
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_material_type', array($this, 'getMaterialType')),
        );
    }

    public function getMaterialType(MaterialType $material)
    {
        return $this->materials[$this->getClassName($material)];
    }

    public function getName()
    {
        return 'celsius3_core.material_type_extension';
    }
}
