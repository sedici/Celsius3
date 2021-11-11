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

use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\Instance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Celsius3\Manager\StateManager;
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
                        'class' => BaseUser::class,
                    ))
            ;
        }

        $builder
                ->add('code', null, array(
                    'required' => false,
                ))
                ->add('type', ChoiceType::class, array(
                    'choices_as_values' => true,
                    'required' => false,
                    'choices' => array(
                        '' => '',
                        'Provision' => 0,
                        'Search' => 1,
                    ),
                ))
                ->add('state', ChoiceType::class, array(
                    'choices_as_values' => true,
                    'required' => false,
                    'choices' => array(
                        /** @Ignore */ ucfirst(StateManager::STATE__CREATED) => StateManager::STATE__CREATED,
                        /** @Ignore */ ucfirst(StateManager::STATE__SEARCHED) => StateManager::STATE__SEARCHED,
                        /** @Ignore */ ucfirst(StateManager::STATE__REQUESTED) => StateManager::STATE__REQUESTED,
                        /** @Ignore */ str_replace('_', ' ', ucfirst(StateManager::STATE__APPROVAL_PENDING)) => StateManager::STATE__APPROVAL_PENDING,
                        /** @Ignore */ ucfirst(StateManager::STATE__RECEIVED) => StateManager::STATE__RECEIVED,
                        /** @Ignore */ ucfirst(StateManager::STATE__DELIVERED) => StateManager::STATE__DELIVERED,
                        /** @Ignore */ ucfirst(StateManager::STATE__CANCELLED) => StateManager::STATE__CANCELLED,
                        /** @Ignore */ ucfirst(StateManager::STATE__ANNULLED) => StateManager::STATE__ANNULLED,
                            ),
                    'multiple' => true,
                    'expanded' => true,
                ))
        ;

        if (is_null($options['instance'])) {
            $builder
                    ->add('instance', EntityType::class, array(
                        'required' => false,
                        'class' => Instance::class,
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
