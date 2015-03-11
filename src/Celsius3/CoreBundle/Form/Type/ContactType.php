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
use Celsius3\CoreBundle\Entity\BaseUser;

class ContactType extends AbstractType
{
    private $user;
    
    public function __construct(BaseUser $user = null)
    {
        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name')
                ->add('surname')
                ->add('email')
                ->add('address')
                ->add('user', 'celsius3_corebundle_user_selector', array(
                    'attr' => array(
                        'class' => 'container',
                        'readonly' => 'readonly',
                        'value' => (!is_null($this->user)) ? $this->user->getId() : '',
                    ),
                ))
                ->add('user_autocomplete', 'text', array(
                    'attr' => array(
                        'value' => $this->user,
                        'class' => 'autocomplete',
                        'target' => 'BaseUser',
                    ),
                    'mapped' => false,
                    'label' => 'User',
                ))
                ->add('type')
                ->add('instance')
        ;
    }

    public function getName()
    {
        return 'celsius3_corebundle_contacttype';
    }
}
