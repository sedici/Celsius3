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

use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function array_key_exists;

class BaseUserType extends RegistrationFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder->get('birthdate')->setAttribute('required', false);
        $builder
            ->add(
                'enabled',
                null,
                [
                    'required' => false,
                    'attr' => ['class' => 'checkbox']
                ]
            )
            ->add(
                'locked',
                null,
                [
                    'required' => false,
                ]
            )
            ->add(
                'pdf',
                null,
                [
                    'required' => false,
                ]
            )
            ->add(
                'downloadAuth',
                null,
                [
                    'required' => false,
                ]
            );

        if (array_key_exists('instance', $options) && $options['instance'] !== null) {
            if ($options['instance']->getUrl() === InstanceManager::INSTANCE__DIRECTORY) {
                $builder
                    ->add(
                        'instance',
                        null,
                        [
                            'query_builder' => static function (EntityRepository $repository) {
                                return $repository->findAllExceptDirectory();
                            },
                        ]
                    );
            } else {
                $builder
                    ->add(
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

        if ($options['editing']) {
            $builder->remove('plainPassword');
            $builder->remove('recaptcha');
        }

        $builder->add(
            'observaciones',
            TextareaType::class,
            [
                'attr' => [
                    'class' => 'summernote',
                ],
                'required' => false,
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'instance' => null,
                'editing' => false,
                'registration' => false,
                'show_privates' => true
            ]
        );
    }
}
