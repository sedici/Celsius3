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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

final class OrdersDataRequestType extends DataRequestType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('date', CheckboxType::class, [
                'label' => 'Date',
                'required' => false,
                'mapped' => false
            ])
            ->add('type', CheckboxType::class, [
                'label' => 'Type',
                'required' => false,
                'mapped' => false
            ])
            ->add('states', ChoiceType::class, [
                'label' => 'Order states dates',
                'required' => false,
                'expanded' => true,
                'multiple' => true,
                'choices' => [
                    'Created_s' => 'created',
                    'Annulled' => 'annulled',
                    'Cancelled_s' => 'cancelled',
                    'Searched' => 'searched',
                    'Requested' => 'requested',
                    'Received' => 'received',
                    'Delivered_s' => 'delivered'
                ],
                'mapped' => false
            ])
            ->add('materialType', CheckboxType::class, [
                'label' => 'Material type',
                'required' => false,
                'mapped' => false
            ])
            ->add('title', CheckboxType::class, [
                'label' => 'Title',
                'required' => false,
                'mapped' => false
            ])
            ->add('authors', CheckboxType::class, [
                'label' => 'Authors',
                'required' => false,
                'mapped' => false
            ])
            ->add('year', CheckboxType::class, [
                'label' => 'Year',
                'required' => false,
                'mapped' => false
            ])
            ->add('startPage', CheckboxType::class, [
                'label' => 'Start page',
                'required' => false,
                'mapped' => false
            ])
            ->add('endPage', CheckboxType::class, [
                'label' => 'End page',
                'required' => false,
                'mapped' => false
            ])
            ->add('journal', ChoiceType::class, [
                'label' => 'Journal',
                'required' => false,
                'expanded' => true,
                'multiple' => true,
                'choices' => [
                    'Name' => 'name',
                    'Volume' => 'volume',
                    'Number' => 'number',
                    'ISSN' => 'issn',
                    'ISSNE' => 'issne',
                    'Responsible' => 'responsible',
                    'Frecuency' => 'frecuency',
                    'Abbreviation' => 'abbreviation'
                ],
                'mapped' => false
            ])
            ->add('book', ChoiceType::class, [
                'label' => 'Book',
                'required' => false,
                'expanded' => true,
                'multiple' => true,
                'choices' => [
                    'Editor' => 'editor',
                    'Chapter' => 'chapter',
                    'ISBN' => 'isbn'
                ],
                'mapped' => false
            ])
            ->add('thesis', ChoiceType::class, [
                'label' => 'Thesis',
                'required' => false,
                'expanded' => true,
                'multiple' => true,
                'choices' => [
                    'Director' => 'director',
                    'Degree' => 'degree'
                ],
                'mapped' => false
            ])
            ->add('congress', ChoiceType::class, [
                'label' => 'Congress',
                'required' => false,
                'expanded' => true,
                'multiple' => true,
                'choices' => [
                    'Place' => 'place',
                    'Communication' => 'communication'
                ],
                'mapped' => false
            ]);
    }
}
