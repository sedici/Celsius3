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

declare(strict_types=1);

namespace Celsius3\CoreBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

final class UsersDataRequestType extends DataRequestType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('email', CheckboxType::class, [
                'label' => 'Email',
                'required' => false,
                'mapped' => false
            ])
            ->add('first_name', CheckboxType::class, [
                'label' => 'Name',
                'required' => false,
                'mapped' => false
            ])
            ->add('surname', CheckboxType::class, [
                'label' => 'Surname',
                'required' => false,
                'mapped' => false
            ])
            ->add('birthdate', CheckboxType::class, [
                'label' => 'Birthdate',
                'required' => false,
                'mapped' => false
            ])
            ->add('address', CheckboxType::class, [
                'label' => 'Address',
                'required' => false,
                'mapped' => false
            ])
            ->add('downloadAuth', CheckboxType::class, [
                'label' => 'Download auth',
                'required' => false,
                'mapped' => false
            ])
            ->add('pdf', CheckboxType::class, [
                'label' => 'pdf',
                'required' => false,
                'mapped' => false
            ])
            ->add('locked', CheckboxType::class, [
                'label' => 'locked',
                'required' => false,
                'mapped' => false
            ])
            ->add('institution', CheckboxType::class, [
                'label' => 'institution',
                'required' => false,
                'mapped' => false
            ])
            ->add('observaciones', CheckboxType::class, [
                'label' => 'observations',
                'required' => false,
                'mapped' => false
            ])
            ->add('lastLogin', CheckboxType::class, [
                'label' => 'Último ingreso',
                'required' => false,
                'mapped' => false
            ])
        ;
    }
}
