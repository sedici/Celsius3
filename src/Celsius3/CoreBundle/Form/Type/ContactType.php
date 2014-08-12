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
use Celsius3\CoreBundle\Document\Instance;

class ContactType extends AbstractType
{

    private $instance;

    public function __construct(Instance $instance = null)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
                ->add('surname')
                ->add('email')
                ->add('address')
                ->add('user')
                ->add('type')
        ;

        if (!is_null($this->instance)) {
            $builder->add('instance', null, array(
                'data' => $this->instance,
                'attr' => array(
                    'value' => $this->instance->getId(),
                    'readonly' => 'readonly',
                ),
            ));
        }
    }

    public function getName()
    {
        return 'celsius3_corebundle_contacttype';
    }

}