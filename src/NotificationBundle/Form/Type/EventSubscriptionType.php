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

namespace Celsius3\NotificationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Celsius3\Manager\EventManager;
use JMS\TranslationBundle\Annotation\Ignore;

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

    private $user_events = array(
        EventManager::EVENT__RECEIVE => 'Receive',
        EventManager::EVENT__CANCEL => 'Cancel',
        EventManager::EVENT__ANNUL => 'Annul',
    );

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $events = $options['is_admin'] ? $this->events : $this->user_events;
        foreach ($events as $key => $label) {
            $builder
                    ->add($key . '_notification', ChoiceType::class, array(
                        'choices_as_values' => true,
                        'choices' => array(
                            /** @Ignore */ 'Notification' => 'notification',
                            /** @Ignore */ 'Email' => 'email',
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
            'is_admin' => false,
        ));
    }
}
