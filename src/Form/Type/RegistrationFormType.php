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

namespace Celsius3\Form\Type;

use Celsius3\Entity\BaseUser;
use Celsius3\Form\EventListener\AddCustomFieldsSubscriber;
use Celsius3\Form\EventListener\AddInstitutionFieldsSubscriber;
use Celsius3\Helper\InstanceHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    protected $entityManager;
    protected $instanceHelper;

    public function __construct(EntityManagerInterface $entityManager, InstanceHelper $instanceHelper)
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
                    'attr' => [
                        'class' => 'date',
                        'placeholder' => 'birthday'
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
            )
            ->add('email')
            ->add('username')
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                                     'message' => 'Please enter a password',
                                 ]),
                    new Length([
                                   'min' => 6,
                                   'minMessage' => 'Your password should be at least {{ limit }} characters',
                                   // max length allowed by Symfony for security reasons
                                   'max' => 4096,
                               ]),
                ],
            ])
        ;

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

//    public function getParent(): string
//    {
//        return FOSRegistrationFormType::class;
//    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'show_privates' => false,
                'data_class' => BaseUser::class,
            ]
        );
    }
}
