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

namespace Celsius3\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderReceiveType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('deliverytype', 'choice', array(
                    'choices' => array('PDF' => 'PDF', 'Printed' => 'Printed'),
                    'label' => 'Delivery Type',
                ))
                ->add('observations', 'textarea', array(
                    'required' => false,
                ))
                ->add('files', 'collection', array(
                    'label' => 'Files',
                    'type' => 'file',
                    'prototype' => true,
                    'allow_add' => true,
                    'allow_delete' => true,
                ))
        ;
    }

    public function getName()
    {
        return 'celsius3_corebundle_orderreceivetype';
    }
}