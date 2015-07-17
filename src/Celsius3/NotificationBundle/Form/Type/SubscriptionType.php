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

namespace Celsius3\NotificationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriptionType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('user_notification', 'choice', array(
                    'choices' => array(
                        'notification' => 'Notification',
                        'email' => 'Email',
                    ),
                    'required' => false,
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'New User',
                ))
                ->add('message_notification', 'choice', array(
                    'choices' => array(
                        'notification' => 'Notification',
                        'email' => 'Email',
                    ),
                    'required' => false,
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'New Message',
                ))
                ->add('event_notification', new EventSubscriptionType(), array(
                    'label' => 'Order Events',
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
        ));
    }

    public function getName()
    {
        return 'celsius3_notificationbundle_subscriptiontype';
    }
}