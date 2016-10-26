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

namespace Celsius3\CoreBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManager;
use JMS\TranslationBundle\Annotation\Ignore;
use Celsius3\CoreBundle\Entity\Instance;

class AddCustomFieldsSubscriber implements EventSubscriberInterface
{

    private $factory;
    private $em;
    private $instance;
    private $registration;

    public function __construct(FormFactoryInterface $factory, EntityManager $em, Instance $instance, $registration)
    {
        $this->factory = $factory;
        $this->em = $em;
        $this->instance = $instance;
        $this->registration = $registration;
    }

    public static function getSubscribedEvents()
    {
        return array(FormEvents::POST_SET_DATA => 'postSetData');
    }

    public function postSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $userId = $data->getId() ? $data->getId() : null;

        $fields = $this->em->getRepository('Celsius3CoreBundle:CustomUserField')
                        ->getByInstance($this->instance, $this->registration);

        foreach ($fields as $field) {
            if ($userId) {
                $value = $this->em->getRepository('Celsius3CoreBundle:CustomUserValue')
                        ->findOneBy(array('field' => $field->getId(), 'user' => $userId));
            } else {
                $value = null;
            }

            if ($field->getType() == 'Symfony\Component\Form\Extension\Core\Type\ChoiceType') {
                $valores = explode(',', $field->getValue());
                $array_choices = array();
                foreach ($valores as $key => $value) {
                    $array_choices[$value] = $value;
                }
                $form->add($field->getKey(), $field->getType(), array(
                    'choices' => $array_choices,
                    'required' => $field->getRequired(),
                    'mapped' => false,
                    // *this line is important*
                    'choices_as_values' => true,
                    'auto_initialize' => false,
                ));
            } else {
                if ($field->getType() == 'Symfony\Component\Form\Extension\Core\Type\DateType') {
                    $form->add($this->factory->createNamed($field->getKey(), $field->getType(), $value ? new \DateTime($value->getValue()) : null, array(
                                /** @Ignore */ 'label' => ucfirst($field->getName()),
                                'required' => $field->getRequired(),
                                'widget' => 'single_text',
                                'format' => 'dd-MM-yyyy',
                                'attr' => array(
                                    'class' => 'date',
                                ),
                                'mapped' => false,
                                'auto_initialize' => false,
                    )));
                } else {
                    $form->add($this->factory->createNamed($field->getKey(), $field->getType(), $value ? $value->getValue() : null, array(
                                /** @Ignore */ 'label' => ucfirst($field->getName()),
                                'required' => $field->getRequired(),
                                'mapped' => false,
                                'auto_initialize' => false,
                    )));
                }
            }
        }
    }

}
