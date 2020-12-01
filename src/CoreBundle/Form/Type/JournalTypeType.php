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

use Celsius3\CoreBundle\Entity\JournalType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class JournalTypeType extends MaterialTypeType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'journal',
                JournalSelectorType::class,
                [
                    'attr' => [
                        'required' => true,
                        'value' => ($options['journal'] !== null)
                            ? $options['journal']->getId()
                            : $options['journal_id'] ?? '',
                        'class' => 'container',
                        'readonly' => 'readonly',
                    ],
                ]
            )
            ->add(
                'journal_autocomplete',
                TextType::class,
                [
                    'attr' => [
                        'required' => true,
                        'class' => 'autocomplete',
                        'target' => 'Journal',
                        'value' => $options['journal'] ?? $options['other'],
                    ],
                    'mapped' => false,
                    'label' => 'Journal',
                    'required' => true,
                    'constraints' => [
                        new NotBlank()
                    ]
                ]
            );

        parent::buildForm($builder, $options);

        $builder
            ->add('volume')
            ->add('number');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => JournalType::class,
                'journal' => null,
                'other' => '',
                'journal_id' => '',
            ]
        );
    }
}
