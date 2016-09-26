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

namespace Celsius3\CoreBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InstanceRegisterType extends LegacyInstanceType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
                ->remove('url')
                ->remove('host')
            ->remove('hive')
            ->add('observaciones',TextareaType::class)
                ->add('country', EntityType::class, array(
                    'class' => 'Celsius3CoreBundle:Country',
                    'mapped' => false,
                    'placeholder' => '',
                    'required' => false,
                    'attr' => array(
                        'class' => 'country-select'
                    ),
                    'auto_initialize' => false,
                ))

            ->add('city', EntityType::class, array(
                'class' => 'Celsius3CoreBundle:City',
                'mapped' => false,
                'placeholder' => '',
                'required' => false,
                'attr' => array(
                    'class' => 'city-select'
                ),
                'auto_initialize' => false,
            ))

            ->add('institution', EntityType::class, array(
                'class' => 'Celsius3CoreBundle:Institution',
                'mapped' => false,
                'label' => ucfirst('institution'),
                'placeholder' => '',
                'required' => false,
                'attr' => array(
                    'class' => 'institution-select'
                ),
                'auto_initialize' => false,
            ))
        ;


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(
                array(
                    'allow_extra_fields' => true
                )
        );
    }

}
