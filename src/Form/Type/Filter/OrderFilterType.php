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

use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Instance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Celsius3\Manager\StateManager;


class OrderFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod('GET');

        if ($options['owner'] === null) {
            $builder->add('owner', EntityType::class, [
                'required' => false,
                'class' => BaseUser::class,
                'data' => $options['owner'],
                'mapped' => false,
            ]);
        }

        $builder
            ->add('code', null, [
                'required' => false,
            ])
            ->add('type', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    '' => '',
                    'Provision' => 0,
                    'Search' => 1,
                ],
                'mapped' => false,
                'data' => $options['type'],
            ])
            ->add('state', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    ucfirst(StateManager::STATE__CREATED) => StateManager::STATE__CREATED,
                    ucfirst(StateManager::STATE__SEARCHED) => StateManager::STATE__SEARCHED,
                    ucfirst(StateManager::STATE__REQUESTED) => StateManager::STATE__REQUESTED,
                    str_replace('_', ' ', ucfirst(StateManager::STATE__APPROVAL_PENDING)) => StateManager::STATE__APPROVAL_PENDING,
                    ucfirst(StateManager::STATE__RECEIVED) => StateManager::STATE__RECEIVED,
                    ucfirst(StateManager::STATE__DELIVERED) => StateManager::STATE__DELIVERED,
                    ucfirst(StateManager::STATE__CANCELLED) => StateManager::STATE__CANCELLED,
                    ucfirst(StateManager::STATE__ANNULLED) => StateManager::STATE__ANNULLED,
                ],
                'multiple' => true,
                'expanded' => true,
                'mapped' => false,
                'data' => $options['state'],
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
            'owner' => null,
            'type' => null,
            'state' => null,
            'instance' => null,
            'validation_groups' => ['base_order_filter_type'],
        ]);
    }
}
