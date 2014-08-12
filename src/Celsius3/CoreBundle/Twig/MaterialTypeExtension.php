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

use Celsius3\CoreBundle\Document\MaterialType;

class MaterialTypeExtension extends \Twig_Extension
{

    private $environment;
    private $materials = array(
        'BookType' => array(
            'template' => '_book.html.twig',
            'class' => 'book',
        ),
        'CongressType' => array(
            'template' => '_congress.html.twig',
            'class' => 'congress',
        ),
        'JournalType' => array(
            'template' => '_journal.html.twig',
            'class' => 'journal',
        ),
        'PatentType' => array(
            'template' => '_patent.html.twig',
            'class' => 'patent',
        ),
        'ThesisType' => array(
            'template' => '_thesis.html.twig',
            'class' => 'thesis',
        ),
    );

    private function getClassName(MaterialType $material)
    {
        $class = explode('\\', get_class($material));

        return end($class);
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
            'render_material' => new \Twig_Function_Method($this, 'renderMaterial'),
            'get_material_type' => new \Twig_Function_Method($this, 'getMaterialType'),);
    }

    public function renderMaterial(MaterialType $material)
    {
        return $this->environment->render('Celsius3CoreBundle:MaterialType:' . $this->materials[$this->getClassName($material)]['template'], array(
                    'materialData' => $material,
        ));
    }

    public function getMaterialType(MaterialType $material)
    {
        return $this->materials[$this->getClassName($material)]['class'];
    }

    public function getName()
    {
        return 'celsius3_core.material_type_extension';
    }

}