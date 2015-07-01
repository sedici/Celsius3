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
use Celsius3\CoreBundle\Manager\EventManager;

class EventSubscriptionType extends AbstractType
{
    private $events = array(
        EventManager::EVENT__CREATION => 'Creation',
        EventManager::EVENT__SEARCH => 'Search',
        EventManager::EVENT__REQUEST => 'Request',
        EventManager::EVENT__RECEIVE => 'Receive',
        EventManager::EVENT__DELIVER => 'Deliver',
        EventManager::EVENT__CANCEL => 'Cancel',
        EventManager::EVENT__ANNUL => 'Annul',
    );

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->events as $key => $label) {
            $builder
                    ->add($key . '_notification', 'choice', array(
                        'choices' => array(
                            'notification' => 'Notification',
                            'email' => 'Email',
                        ),
                        'required' => false,
                        'multiple' => true,
                        'expanded' => true,
                        'label' => $label,
                    ))
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
        ));
    }

    public function getName()
    {
        return 'celsius3_notificationbundle_eventsubscriptiontype';
    }
}
