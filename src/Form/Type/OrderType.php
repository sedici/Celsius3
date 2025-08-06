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

use Celsius3\Entity\Order;
use Celsius3\Manager\MaterialTypeManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;


class OrderType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $class = explode('\\', (string) $options['material']);
        $preferredMaterial = lcfirst(str_replace(
            'Type', '', end($class)
        ));


        $materialOptions = [ 'constraints' => new Valid() ];

        if ($preferredMaterial === 'journal') {
            $materialOptions['journal'] = $options['journal'];
            $materialOptions['other'] = $options['other'];
            $materialOptions['journal_id'] = $options['journal_id'] ?? '' ;
        }


        $builder
            ->add(
                'originalRequest',
                RequestType::class,
                [
                    'label' => false,
                    'instance' => $options['instance'],
                    'user' => $options['user'],
                    'operator' => $options['operator'],
                    'librarian' => $options['librarian'],
                    'create' => $options['create'],
                    'target' => $options['target']
                ]
            )
            ->add(
                'materialDataType',
                ChoiceType::class,
                [
                    'choices' => MaterialTypeManager::CHOICES__MAP,
                    'mapped' => false,
                    'data' => $preferredMaterial,
                    'label' => 'Material Type'
                ]
            )
            ->add(
                'materialData',
                $options['material'],
                $materialOptions
            );

        if (
            array_key_exists('actual_user', $options)
            && $options['actual_user'] !== null
        ) {
            if (
                $options['actual_user']->hasRole('ROLE_ADMIN')
                || $options['actual_user']->hasRole('ROLE_SUPER_ADMIN')
            ) {
                $builder->add(
                    'save_and_show',
                    SubmitType::class,
                    [
                        'attr' => [
                            'class' => 'btn btn-primary submit-button pull-left',
                        ],
                    'label' => 'save_and_show',
                    ]
                );
            }
        }
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'instance' => null,
            'material' => JournalTypeType::class,
            'user' => null,
            'operator' => null,
            'librarian' => false,
            'actual_user' => null,
            'journal' => null,
            'other' => '',
            'journal_id' => '',
            'create' => false,
            'target' => ''
        ]);
    }
}
