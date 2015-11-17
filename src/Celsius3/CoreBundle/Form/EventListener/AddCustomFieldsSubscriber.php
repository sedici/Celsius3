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

namespace Celsius3\CoreBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
        return array(FormEvents::POST_SET_DATA => 'postSetData',);
    }

    public function postSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        // During form creation setData() is called with null as an argument
        // by the FormBuilder constructor. You're only concerned with when
        // setData is called with an actual Entity object in it (whether new
        // or fetched with Doctrine). This if statement lets you skip right
        // over the null condition.
        if (null === $data) {
            return;
        }

        $userId = $data->getId() ? $data->getId() : null;

        $query = $this->em->getRepository('Celsius3CoreBundle:CustomUserField')
                ->createQueryBuilder('cuf')
                ->where('cuf.instance = :instance_id')
                ->setParameter('instance_id', $this->instance->getId());

        if ($this->registration) {
            $query = $query->andWhere('cuf.private = true');
        }

        $fields = $query->getQuery()->getResult();

        foreach ($fields as $field) {
            if ($userId) {
                $value = $this->em->getRepository('Celsius3CoreBundle:CustomUserValue')
                        ->findOneBy(array(
                    'field' => $field->getId(),
                    'user' => $userId,
                ));
            } else {
                $value = null;
            }

            $form->add($this->factory->createNamed($field->getKey(), TextType::class, $value ? $value->getValue() : null, array(
                        /** @Ignore */ 'label' => ucfirst($field->getName()),
                        'required' => $field->getRequired(),
                        'mapped' => false,
                        'auto_initialize' => false,
            )));
        }
    }
}
