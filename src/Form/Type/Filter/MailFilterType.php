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

namespace Celsius3\Form\Type\Filter;

use Celsius3\Entity\Instance;
use Celsius3\Form\Type\UserSelectorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormTypeInterface;

class MailFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod('GET');
        
        $builder
            ->add('sender', UserSelectorType::class, [
                'attr' => [
                    'class' => 'container',
                    'readonly' => 'readonly',
                    'value' => ($options['sender'] !== null) ? $options['sender']->getId() : null,
                ],
                'required' => false,
            ])
            ->add('address', null, [
                'required' => false,
            ]);
                
        if ($options['instance'] === null) {
            $builder->add('instance', EntityType::class, [
                'required' => false,
                'class' => Instance::class,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'instance' => null,
            'sender' => null,
            'allow_extra_fields' => true,
            'validation_groups' => ['base_mail_filter_type'],
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
