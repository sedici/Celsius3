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
use JMS\TranslationBundle\Annotation\Ignore;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (array_key_exists('operator', $options) && !is_null($options['operator'])) {
            $builder->add('type', ChoiceType::class, array(
                    'choices' => array(
                        /** @Ignore */ OrderManager::TYPE__SEARCH => OrderManager::TYPE__SEARCH,
                        /** @Ignore */ ucfirst(OrderManager::TYPE__PROVISION) => OrderManager::TYPE__PROVISION,
                    ),
//                    'choices_as_values' => true,
                ));
        } else {
            $builder->add('type', HiddenType::class, array(
                    'data' => OrderManager::getTypeForUser($options['instance'], $options['user']),
                    'attr' => array(
                        'readonly' => 'readonly',
                        'value' => OrderManager::getTypeForUser($options['instance'], $options['user']),
                    ),
                ));
        }

        $builder
                ->add('comments', TextareaType::class, array(
                    'required' => false,
                ))
                ->add('owner', UserSelectorType::class, array(
                    'attr' => array(
                        'value' => (!is_null($options['user'])) ? $options['user']->getId() : '',
                        'class' => 'container',
                        'readonly' => 'readonly',
                    ),
                ))
                ->add('creator', UserSelectorType::class, array(
                    'attr' => array(
                        'value' => (!is_null($options['operator'])) ? $options['operator']->getId() : ((!is_null($options['user'])) ? $options['user']->getId() : ''),
                        'class' => 'container',
                        'readonly' => 'readonly',
                    ),
                ))
        ;

        if ($options['librarian']) {
            $builder
                    ->add('target', ChoiceType::class, array(
                        'choices' => array(
                            'Me' => 'me',
                            'Other' => 'other',
                        ),
//                        'choices_as_values' => true,
                        'mapped' => false,
                    ))
                    ->add('librarian', UserSelectorType::class, array(
                        'attr' => array(
                            'readonly' => 'readonly',
                        ),
                    ))
                    ->add('owner_autocomplete', TextType::class, array(
                        'attr' => array(
                            'class' => 'autocomplete',
                            'target' => 'BaseUser',
                            'value' => (!is_null($options['user'])) ? $options['user'] : '',
                        ),
                        'mapped' => false,
                        'label' => 'Owner',
                        'required' => true,
                        'constraints' => $options['target'] === 'other' ? [new \Symfony\Component\Validator\Constraints\NotBlank()] : []
                    ))
            ;
        }

        if ($options['operator'] !== null) {
            $builder
                    ->add('owner_autocomplete', TextType::class, array(
                        'attr' => array(
                            'class' => 'autocomplete',
                            'target' => 'BaseUser',
                            'value' => (!is_null($options['user'])) ? $options['user'] : '',
                        ),
                        'mapped' => false,
                        'label' => 'Owner',
                        'required' => true,
                        'constraints' => array(
                            new \Symfony\Component\Validator\Constraints\NotBlank()
                        )
                    ))
                    ->add('operator', UserSelectorType::class, array(
                        'attr' => array(
                            'value' => (!$options['create']) ? $options['operator']->getId() : null,
                            'class' => 'container',
                            'readonly' => 'readonly',
                        ),
                    ))
            ;
        }
        
        if (array_key_exists('instance', $options) && $options['instance'] !== null) {
            if ($options['instance']->getUrl() === InstanceManager::INSTANCE__DIRECTORY) {
                $builder
                        ->add('instance', null, array(
                            'query_builder' => function (EntityRepository $repository) {
                                return $repository->findAllExceptDirectory();
                            },
                        ))
                ;
            } else {
                $builder->add('instance', InstanceSelectorType::class, array(
                    'data' => $options['instance'],
                    'attr' => array(
                        'value' => $options['instance']->getId(),
                        'readonly' => 'readonly',
                    ),
                ));
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Request::class,
            'instance' => null,
            'user' => null,
            'operator' => null,
            'librarian' => false,
            'create' => false,
            'target' => ''
        ));
    }
}
