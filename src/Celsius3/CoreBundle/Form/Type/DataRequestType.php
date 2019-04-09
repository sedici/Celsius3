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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class DataRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required' => false
            ])
            ->add('initialDate', DateType::class, [
                'label' => 'Initial date',
                'data' => \DateTime::createFromFormat('Y-m-d', '1990-01-01'),
                'widget' => 'single_text'
            ])
            ->add('finalDate', DateType::class, [
                'label' => 'Final date',
                'data' => new \DateTime('now'),
                'widget' => 'single_text'
            ])
            ->add('date', CheckboxType::class, [
                'label' => 'Date',
                'required' => false,
            ])
            ->add('type', CheckboxType::class, [
                'label' => 'Type',
                'required' => false,
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
            ])
            ->add('materialType', CheckboxType::class, [
                'label' => 'Material type',
                'required' => false,
            ])
            ->add('title', CheckboxType::class, [
                'label' => 'Title',
                'required' => false,
            ])
            ->add('authors', CheckboxType::class, [
                'label' => 'Authors',
                'required' => false,
            ])
            ->add('year', CheckboxType::class, [
                'label' => 'Year',
                'required' => false,
            ])
            ->add('startPage', CheckboxType::class, [
                'label' => 'Start page',
                'required' => false,
            ])
            ->add('endPage', CheckboxType::class, [
                'label' => 'End page',
                'required' => false,
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
                ]
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
                ]
            ])
            ->add('thesis', ChoiceType::class, [
                'label' => 'Thesis',
                'required' => false,
                'expanded' => true,
                'multiple' => true,
                'choices' => [
                    'Director' => 'director',
                    'Degree' => 'degree'
                ]
            ])
            ->add('congress', ChoiceType::class, [
                'label' => 'Congress',
                'required' => false,
                'expanded' => true,
                'multiple' => true,
                'choices' => [
                    'Place' => 'place',
                    'Communication' => 'communication'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Request data'
            ]);

    }
}