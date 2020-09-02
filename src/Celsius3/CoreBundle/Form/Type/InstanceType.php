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

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function array_key_exists;

class InstanceType extends LegacyInstanceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('url')
            ->add('host')
            ->add('latitud')
            ->add('longitud');

        if (array_key_exists('institution_select', $options) && $options['institution_select']) {
            $builder->add(
                'country',
                EntityType::class,
                [
                    'class' => 'Celsius3CoreBundle:Country',
                    'mapped' => false,
                    'placeholder' => '',
                    'required' => true,
                    'attr' => [
                        'class' => 'country-select',
                    ],
                    'auto_initialize' => false,
                ]
            );

            $builder->add(
                'city',
                EntityType::class,
                [
                    'class' => 'Celsius3CoreBundle:City',
                    'mapped' => false,
                    'placeholder' => '',
                    'required' => false,
                    'attr' => [
                        'class' => 'city-select',
                    ],
                    'auto_initialize' => false,
                ]
            );

            $builder->add(
                'institution',
                EntityType::class,
                [
                    'class' => 'Celsius3CoreBundle:Institution',
                    'mapped' => false,
                    'label' => ucfirst('institution'),
                    'placeholder' => '',
                    'required' => true,
                    'attr' => [
                        'class' => 'institution-select',
                    ],
                    'auto_initialize' => false,
                ]
            );
        }

        $builder->add(
            'observaciones',
            TextareaType::class,
            [
                'attr' => [
                    'class' => 'summernote',
                ],
                'required' => false,
            ]
        );

        $builder->add('inTestMode')
            ->add('testModeLimitDate');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            [
                'allow_extra_fields' => true,
                'institution_select' => false,
            ]
        );
    }
}
