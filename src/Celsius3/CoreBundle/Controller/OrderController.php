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

namespace Celsius3\CoreBundle\Controller;
use Celsius3\CoreBundle\Entity\Order;
use Celsius3\CoreBundle\Form\Type\OrderType;

abstract class OrderController extends BaseInstanceDependentController
{

    protected function listQuery($name)
    {
        return parent::listQuery($name)->field('currentState')->prime(true);
    }

    protected function change()
    {
        $material = 'Celsius3\\CoreBundle\\Form\\Type\\'
                . ucfirst($this->getRequest()->get('material')) . 'TypeType';

        if (!class_exists($material))
            $this->createNotFoundException('Inexistent Material Type');

        $type = new OrderType($this->getInstance(), new $material);
        $form = $this->createForm($type, new Order());

        return $this
                ->render(
                        'Celsius3CoreBundle:Order:_materialData.html.twig',
                        array('form' => $form->createView()));
    }

    protected function getMaterialType($materialData = null)
    {
        if (is_null($materialData)) {
            $materialTypeName = 'Celsius3\\CoreBundle\\Form\\Type\\'
                    . ucfirst(
                            $this->getRequest()->request
                                    ->get(
                                            'celsius3_corebundle_ordertype[materialDataType]',
                                            null, true)) . 'TypeType';
        } else {
            $class = explode('\\', $materialData);
            $materialTypeName = 'Celsius3\\CoreBundle\\Form\\Type\\'
                    . end($class) . 'Type';
        }

        return new $materialTypeName;
    }

}