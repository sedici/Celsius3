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

use Celsius3\CoreBundle\Manager\InstanceManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('entity', ChoiceType::class, [
                'choices' => [
                    'User' => 'BaseUser',
                    'Contact' => 'Contact',
                ],
                'choices_as_values' => true,
                'required' => true,
                'label' => 'Associated to'
            ])
            ->add('name')
            ->add('private', CheckboxType::class, [
                'required' => false,
            ])
            ->add('required', CheckboxType::class, [
                'required' => false,
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Seleccion' => ChoiceType::class,
                    'Texto' => TextType::class,
                    'Fecha' => DateType::class,
                ],
                'choices_as_values' => true,
            ])
            ->add('value', TextType::class, [
                'label' => 'Value',
                'required' => false,
            ]);

        if (array_key_exists('instance', $options) && !is_null($options['instance'])) {
            if ($options['instance']->getUrl() === InstanceManager::INSTANCE__DIRECTORY) {
                $builder->add('instance');
            } else {
                $builder->add('instance', InstanceSelectorType::class, [
                    'data' => $options['instance'],
                    'attr' => [
                        'value' => $options['instance']->getId(),
                        'readonly' => 'readonly',
                    ],
                ]);
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'instance' => null,
        ]);
    }
}
