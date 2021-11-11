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

namespace Celsius3\Form\Type;

use Celsius3\CoreBundle\Entity\City;
use Celsius3\CoreBundle\Entity\Country;
use Celsius3\CoreBundle\Entity\Institution;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstanceType extends LegacyInstanceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
                ->add('url')
                ->add('host')
            ->add('latitud')
            ->add('longitud')
        ;

        if (array_key_exists('institution_select', $options) && $options['institution_select']) {
            $builder->add('country', EntityType::class, array(
                'class' => Country::class,
                'mapped' => false,
                'placeholder' => '',
                'required' => true,
                'attr' => array(
                    'class' => 'country-select',
                ),
                'auto_initialize' => false,
            ));

            $builder->add('city', EntityType::class, array(
                'class' => City::class,
                'mapped' => false,
                'placeholder' => '',
                'required' => false,
                'attr' => array(
                    'class' => 'city-select',
                ),
                'auto_initialize' => false,
            ));

            $builder->add('institution', EntityType::class, array(
                'class' => Institution::class,
                'mapped' => false,
                'label' => ucfirst('institution'),
                'placeholder' => '',
                'required' => true,
                'attr' => array(
                    'class' => 'institution-select',
                ),
                'auto_initialize' => false,
            ));
        }

        $builder->add('observaciones', TextareaType::class, array(
            'attr' => array(
                'class' => 'summernote',
            ),
            'required' => false,
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
                array(
                    'allow_extra_fields' => true,
                    'institution_select' => false,
                )
        );
    }
}
