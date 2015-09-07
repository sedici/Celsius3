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
use Celsius3\CoreBundle\Entity\Journal;

abstract class OrderController extends BaseInstanceDependentController
{

    protected function baseCreate($name, $entity, $type, $route)
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $form = $this->createForm($type, $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($request->request->get($form->getName() . '[materialData][journal]', null, true) === '') {
                $entity->getMaterialData()->setOther($request->request->get($form->getName() . '[materialData][journal_autocomplete]', null, true));
            }

            $this->persistEntity($entity);
            $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'The ' . $name . ' was successfully created.');

            return $this->redirect($this->generateUrl($route));
        }

        $this->get('session')
                ->getFlashBag()
                ->add('error', 'There were errors creating the ' . $name . '.');

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    protected function change()
    {
        $request = $this->get('request_stack')->getCurrentRequest();

        $material = 'Celsius3\\CoreBundle\\Form\\Type\\' . ucfirst($request->get('material')) . 'TypeType';

        if (!class_exists($material)) {
            $this->createNotFoundException('Inexistent Material Type');
        }

        $type = new OrderType($this->getInstance(), new $material);
        $form = $this->createForm($type, new Order());

        return $this->render('Celsius3CoreBundle:Order:_materialData.html.twig', array('form' => $form->createView()));
    }

    protected function getMaterialType($materialData = null, Journal $journal = null, $other = '')
    {
        $request = $this->get('request_stack')->getCurrentRequest();

        if (is_null($materialData)) {
            $materialTypeName = 'Celsius3\\CoreBundle\\Form\\Type\\' . ucfirst($request->request->get('celsius3_corebundle_ordertype[materialDataType]', null, true)) . 'TypeType';
        } else {
            $class = explode('\\', $materialData);
            $materialTypeName = 'Celsius3\\CoreBundle\\Form\\Type\\' . end($class) . 'Type';
        }

        return new $materialTypeName($journal,$other);
    }

}
