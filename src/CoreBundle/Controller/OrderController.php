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

namespace Celsius3\CoreBundle\Controller;

use Celsius3\CoreBundle\Entity\Order;
use Celsius3\CoreBundle\Form\Type\OrderType;

abstract class OrderController extends BaseInstanceDependentController
{
    protected function baseCreate($name, $entity, $type, array $options, $route)
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $form = $this->createForm($type, $entity, $options);
        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($this->getMaterialType() === 'Celsius3\CoreBundle\Form\Type\JournalTypeType') {
                $journal = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Journal')->find(
                    $request->request->get('order', null, true)['materialData']['journal']
                );
                if (is_null($journal) ) {
                    $entity->getMaterialData()->setOther($request->request->get('order', null, true)['materialData']['journal_autocomplete']);
                    $entity->getMaterialData()->setJournal(null);
                }
            }

            $this->persistEntity($entity);
            $this->get('session')
                ->getFlashBag()
                ->add('success', 'The ' . $name . ' was successfully created.');

            if ($form->has('save_and_show')) {
                if ($form->get('save_and_show')->isClicked()) {
                    return $this->redirect($this->generateUrl('admin_order_show', array('id' => $entity->getId())));
                }
            }

            return $this->redirect($this->generateUrl($route));
        }

        $this->get('session')
            ->getFlashBag()
            ->add('error', 'There were errors creating the ' . $name . '.');

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    protected function change()
    {
        $request = $this->get('request_stack')->getCurrentRequest();

        $material = 'Celsius3\\CoreBundle\\Form\\Type\\' . ucfirst($request->get('material')) . 'TypeType';

        if (!class_exists($material)) {
            $this->createNotFoundException('Inexistent Material Type');
        }

        $form = $this->createForm(OrderType::class, new Order(), array(
            'instance' => $this->getInstance(),
            'material' => $material,
            'actual_user' => $this->getUser(),
        ));

        return $this->render('Celsius3CoreBundle:Order:_materialData.html.twig', array(
                'form' => $form->createView(),
                'material' => $request->get('material'),)
        );
    }

    protected function getMaterialType($materialData = null)
    {
        $request = $this->get('request_stack')->getCurrentRequest();

        if (is_null($materialData)) {
            $materialTypeName = 'Celsius3\\CoreBundle\\Form\\Type\\' . ucfirst($request->request->get('order', null, true)['materialDataType']) . 'TypeType';
        } else {
            $class = explode('\\', $materialData);
            $materialTypeName = 'Celsius3\\CoreBundle\\Form\\Type\\' . end($class) . 'Type';
        }

        return $materialTypeName;
    }
}
