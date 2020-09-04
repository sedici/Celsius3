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

declare(strict_types=1);

namespace Celsius3\CoreBundle\Form\Type;

use Celsius3\CoreBundle\Form\EventListener\AddCustomFieldsSubscriber;
use Celsius3\CoreBundle\Form\EventListener\AddInstitutionFieldsSubscriber;
use Celsius3\CoreBundle\Helper\InstanceHelper;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Form\Type\RegistrationFormType as FOSRegistrationFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    protected $entityManager;
    protected $instanceHelper;

    public function __construct(EntityManager $entityManager, InstanceHelper $instanceHelper)
    {
        $this->entityManager = $entityManager;
        $this->instanceHelper = $instanceHelper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                null,
                [
                    'label' => 'Name',
                ]
            )
            ->add('surname')
            ->add(
                'birthdate',
                BirthdayType::class,
                [
                    'required' => false,
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                    'attr' => [
                        'class' => 'date',
                    ],
                ]
            )
            ->add(
                'address',
                null,
                [
                    'required' => false,
                ]
            )
            ->add(
                'instance',
                InstanceSelectorType::class,
                [
                    'data' => $this->instanceHelper->getSessionOrUrlInstance(),
                    'attr' => [
                        'value' => $this->instanceHelper->getSessionOrUrlInstance()->getId(),
                        'readonly' => 'readonly',
                    ],
                ]
            );

        $custom_fileds_subscriber = new AddCustomFieldsSubscriber(
            'BaseUser',
            $builder->getFormFactory(),
            $this->entityManager,
            $this->instanceHelper->getSessionOrUrlInstance(),
            $options['show_privates']
        );
        $builder->addEventSubscriber($custom_fileds_subscriber);

        $institution_fileds_subscriber = new AddInstitutionFieldsSubscriber(
            $builder->getFormFactory(),
            $this->entityManager
        );
        $builder->addEventSubscriber($institution_fileds_subscriber);
    }

    public function getParent(): string
    {
        return FOSRegistrationFormType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'show_privates' => false,
            ]
        );
    }
}
