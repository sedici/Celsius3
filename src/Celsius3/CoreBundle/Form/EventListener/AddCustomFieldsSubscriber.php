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

use Celsius3\CoreBundle\Entity\CustomContactValue;
use Celsius3\CoreBundle\Entity\CustomField;
use Celsius3\CoreBundle\Entity\CustomUserValue;
use Celsius3\CoreBundle\Entity\Instance;
use DateTime;
use Doctrine\ORM\EntityManager;
use JMS\TranslationBundle\Annotation\Ignore;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class AddCustomFieldsSubscriber implements EventSubscriberInterface
{
    private $entity;
    private $factory;
    private $entityManager;
    private $instance;
    private $showPrivates;

    public function __construct(
        string $entity,
        FormFactoryInterface $factory,
        EntityManager $entityManager,
        Instance $instance,
        bool $showPrivates
    )
    {
        $this->entity = $entity;
        $this->factory = $factory;
        $this->entityManager = $entityManager;
        $this->instance = $instance;
        $this->showPrivates = $showPrivates;
    }

    public static function getSubscribedEvents(): array
    {
        return [FormEvents::POST_SET_DATA => 'postSetData'];
    }

    public function postSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $entiy_id = $data->getId() ? $data->getId() : null;

        $fields = $this->entityManager->getRepository(CustomField::class)
            ->getByInstance($this->instance, $this->entity, $this->showPrivates);

        foreach ($fields as $field) {
            if ($entiy_id && 'BaseUser' === $field->getEntity()) {
                $value = $this->entityManager->getRepository(CustomUserValue::class)
                    ->findOneBy(['field' => $field->getId(), 'user' => $entiy_id]);
            } else if ($entiy_id && 'Contact' === $field->getEntity()) {
                $value = $this->entityManager->getRepository(CustomContactValue::class)
                    ->findOneBy(['field' => $field->getId(), 'contact' => $entiy_id]);
            } else {
                $value = null;
            }

            $placeholder = ucfirst($field->getName());
            if ($field->isRequired()) {
                $placeholder .= '*';
            }

            if (ChoiceType::class === $field->getType()) {
                $values = explode(',', $field->getValue());
                $array_choices = [];
                foreach ($values as $key => $val) {
                    $array_choices[$val] = $val;
                }

                $form->add($this->factory->createNamed($field->getKey(), $field->getType(), $value ? $value->getValue() : null, [
                    'choices' => $array_choices,
                    'required' => $field->isRequired(),
                    'mapped' => false,
                    'choices_as_values' => true,
                    'auto_initialize' => false,
                    'attr' => [
                        'class' => 'select2 select2-without-search',
                        'data-placeholder' => $placeholder,
                    ],
                    'constraints' => ($field->isRequired()) ? new NotNull() : null,
                    'label' => $field->getName()
                ]));
            } else if (DateType::class === $field->getType()) {
                $form->add($this->factory->createNamed($field->getKey(), $field->getType(), $value ? new DateTime($value->getValue()) : null, [
                    /* @Ignore */
                    'label' => ucfirst($field->getName()),
                    'required' => $field->isRequired(),
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                    'attr' => [
                        'class' => 'date',
                    ],
                    'mapped' => false,
                    'auto_initialize' => false,
                    'constraints' => ($field->getRequired()) ? new NotNull() : null,
                ]));
            } else if (TextType::class === $field->getType()) {
                $form->add($this->factory->createNamed($field->getKey(), $field->getType(), $value ? $value->getValue() : null, [
                    /* @Ignore */
                    'label' => ucfirst($field->getName()),
                    'required' => $field->isRequired(),
                    'mapped' => false,
                    'auto_initialize' => false,
                    'constraints' => ($field->isRequired()) ? new NotBlank() : null,
                    'attr' => [
                        'placeholder' => $placeholder
                    ]
                ]));
            } else {
                $form->add($this->factory->createNamed($field->getKey(), $field->getType(), $value ? $value->getValue() : null, [
                    /* @Ignore */
                    'label' => ucfirst($field->getName()),
                    'required' => $field->isRequired(),
                    'mapped' => false,
                    'auto_initialize' => false,
                    'constraints' => ($field->isRequired()) ? new NotBlank() : null,
                ]));
            }
        }
    }
}
