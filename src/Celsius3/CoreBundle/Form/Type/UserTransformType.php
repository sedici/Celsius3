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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Celsius3\CoreBundle\Manager\UserManager;

class UserTransformType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array(
            UserManager::ROLE_USER => 'User',
            UserManager::ROLE_LIBRARIAN => 'Librarian',
            UserManager::ROLE_ADMIN => 'Admin',
        );

        if (!is_null($options['instance'])) {
            $builder->add($options['user']->getInstance()->getUrl(), ChoiceType::class, array(
                'choices' => $choices,
                'expanded' => true,
                'multiple' => true,
                'data' => $options['user']->getRoles()
            ));
        } else {
            $choices[UserManager::ROLE_SUPER_ADMIN] = 'Superadmin';

            $builder->add($options['user']->getInstance()->getUrl(), ChoiceType::class, array(
                'choices' => $choices,
                'expanded' => true,
                'multiple' => true,
                'data' => $options['user']->getRoles()
            ));

            foreach ($options['user']->getSecondaryInstances() as $instance) {
                $builder->add($instance['url'], ChoiceType::class, array(
                    'choices' => $choices,
                    'expanded' => true,
                    'multiple' => true,
                    'data' => $instance['roles']
                ));
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'user' => null,
            'instance' => null,
        ));
    }
}
