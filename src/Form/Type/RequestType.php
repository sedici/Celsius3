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

namespace Celsius3\Form\Type;

use Celsius3\Entity\Request;
use Celsius3\Manager\InstanceManager;
use Celsius3\Manager\OrderManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use JMS\TranslationBundle\Annotation\Ignore;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use \Symfony\Component\Validator\Constraints\NotBlank;

class RequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (
            array_key_exists('operator', $options)
            && $options['operator'] !== null
        ) {
            $builder->add(
                'type',
                ChoiceType::class,
                [
                    'choices' => [
                        OrderManager::TYPE__SEARCH => OrderManager::TYPE__SEARCH,
                        ucfirst(OrderManager::TYPE__PROVISION) => OrderManager::TYPE__PROVISION,
                    ]
                ]
            );
        } else {
            $builder->add(
                'type',
                HiddenType::class,
                [
                    'data' => OrderManager::getTypeForUser(
                        $options['instance'], $options['user']
                    ),
                    'attr' => [
                        'readonly' => 'readonly',
                        'value' => OrderManager::getTypeForUser(
                            $options['instance'], $options['user']
                        ),
                    ],
                ]
            );
        }

        $builder
            ->add(
                'comments',
                TextareaType::class,
                [ 'required' => false ]
            )
            ->add(
                'owner',
                UserSelectorType::class,
                [
                    'attr' => [
                        'value' => ($options['user'] !== null)
                            ? $options['user']->getId()
                            : '',
                        'class' => 'container',
                        'readonly' => 'readonly',
                    ],
                ]
            )
            ->add(
                'creator',
                UserSelectorType::class,
                [
                    'attr' => [
                        'value' => ($options['operator'] !==  null)
                            ? $options['operator']->getId()
                            : (($options['user'] !== null)
                                ? $options['user']->getId()
                                : ''),
                        'class' => 'container',
                        'readonly' => 'readonly',
                    ],
                ]
            );

        if ($options['librarian']) {
            $builder
                ->add(
                    'target',
                    ChoiceType::class,
                    [
                        'choices' => [
                            'Me' => 'me',
                            'Other' => 'other',
                        ],
                        'mapped' => false,
                    ]
                )
                ->add(
                    'librarian',
                    UserSelectorType::class,
                    [ 'attr' => [ 'readonly' => 'readonly' ] ]
                )
                ->add(
                    'owner_autocomplete',
                    TextType::class,
                    [
                        'attr' => [
                            'class' => 'autocomplete',
                            'target' => 'BaseUser',
                            'value' => ($options['user'] !== null)
                                ? $options['user']
                                : '',
                        ],
                        'mapped' => false,
                        'label' => 'Owner',
                        'required' => true,
                        'constraints' => $options['target'] === 'other'
                            ? [ new NotBlank() ]
                            : []
                    ]
                );
        }

        if ($options['operator'] !== null) {
            $builder
                ->add(
                    'owner_autocomplete',
                    TextType::class,
                    [
                        'attr' => [
                            'class' => 'autocomplete',
                            'target' => 'BaseUser',
                            'value' => ($options['user'] !== null)
                                ? $options['user']
                                : '',
                        ],
                        'mapped' => false,
                        'label' => 'Owner',
                        'required' => true,
                        'constraints' => [ new NotBlank() ]
                    ]
                )
                ->add(
                    'operator',
                    UserSelectorType::class,
                    [
                        'attr' => [
                            'value' => (!$options['create'])
                                ? $options['operator']->getId()
                                : null,
                            'class' => 'container',
                            'readonly' => 'readonly',
                        ],
                    ]
                );
        }
        
        if (
            array_key_exists('instance', $options)
            && $options['instance'] !== null
        ) {
            if ($options['instance']->getUrl() === InstanceManager::INSTANCE__DIRECTORY) {
                $builder->add(
                    'instance',
                    null,
                    [
                        'query_builder' => 
                            fn (EntityRepository $repository): QueryBuilder =>
                                $repository->findAllExceptDirectory()
                    ]
                );
            } else {
                $builder->add(
                    'instance',
                    InstanceSelectorType::class,
                    [
                        'data' => $options['instance'],
                        'attr' => [
                            'value' => $options['instance']->getId(),
                            'readonly' => 'readonly',
                        ],
                    ]
                );
            }
        }

        // $builder->addEventListener(
        //     FormEvents::POST_SUBMIT,
        //     function (FormEvent $event) use ($options): void {
        //         $form = $event->getForm();
        //         $formOwner = $form->get('owner');

        //         if ($formOwner->getData() === null)
        //             $form
        //                 ->get('owner_autocomplete')
        //                 ->addError(
        //                     new FormError(
        //                         'El usuario seleccionado no es válido'
        //                     )
        //                 );
        //     }
        // );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Request::class,
            'instance' => null,
            'user' => null,
            'operator' => null,
            'librarian' => false,
            'create' => false,
            'target' => ''
        ]);
    }
}
