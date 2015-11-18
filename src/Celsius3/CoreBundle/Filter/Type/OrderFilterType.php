<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

namespace Celsius3\CoreBundle\Filter\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Celsius3\CoreBundle\Manager\StateManager;
use JMS\TranslationBundle\Annotation\Ignore;

/** @Ignore */
class OrderFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('GET');

        if (is_null($options['owner'])) {
            $builder
                    ->add('owner', EntityType::class, array(
                        'required' => false,
                        'class' => 'Celsius3CoreBundle:BaseUser',
                    ))
            ;
        }

        $builder
                ->add('code', null, array(
                    'required' => false,
                ))
                ->add('type', ChoiceType::class, array(
                    'required' => false,
                    'choices' => array(
                        '' => '',
                        0 => 'Provision',
                        1 => 'Search',
                    ),
                ))
                ->add('state', ChoiceType::class, array(
                    'required' => false,
                    'choices' => array(
                        /** @Ignore */ StateManager::STATE__CREATED => ucfirst(
                                StateManager::STATE__CREATED),
                        /** @Ignore */ StateManager::STATE__SEARCHED => ucfirst(
                                StateManager::STATE__SEARCHED),
                        /** @Ignore */ StateManager::STATE__REQUESTED => ucfirst(
                                StateManager::STATE__REQUESTED),
                        /** @Ignore */ StateManager::STATE__APPROVAL_PENDING => str_replace('_', ' ', ucfirst(
                                        StateManager::STATE__APPROVAL_PENDING)),
                        /** @Ignore */ StateManager::STATE__RECEIVED => ucfirst(
                                StateManager::STATE__RECEIVED),
                        /** @Ignore */ StateManager::STATE__DELIVERED => ucfirst(
                                StateManager::STATE__DELIVERED),
                        /** @Ignore */ StateManager::STATE__CANCELLED => ucfirst(
                                StateManager::STATE__CANCELLED),
                        /** @Ignore */ StateManager::STATE__ANNULLED => ucfirst(
                                StateManager::STATE__ANNULLED),),
                    'multiple' => true,
                    'expanded' => true,
                ))
        ;

        if (is_null($options['instance'])) {
            $builder
                    ->add('instance', EntityType::class, array(
                        'required' => false,
                        'class' => 'Celsius3CoreBundle:Instance',
                    ))
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'owner' => null,
            'instance' => null,
        ));
    }
}
