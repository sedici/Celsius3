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

namespace Celsius3\CoreBundle\Form\Type\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use JMS\TranslationBundle\Annotation\Ignore;

class BaseUserFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('GET');

        $builder
                ->add('id', HiddenType::class, array(
                    'required' => false,
                ))
                ->add('name', null, array(
                    'required' => false,
                ))
                ->add('surname', null, array(
                    'required' => false,
                ))
                ->add('username', null, array(
                    'required' => false,
                ))
                ->add('email', null, array(
                    'required' => false,
                ))
                ->add('state', ChoiceType::class, array(
                    'choices_as_values' => true,
                    'required' => false,
                    'choices' => array(
                        /** @Ignore */ 'Enabled' => 'enabled',
                        /** @Ignore */ 'Pending' => 'pending',
                        /** @Ignore */ 'Rejected' => 'rejected',
                    ),
                    'expanded' => true,
                    'multiple' => true,
                ))
                ->add('roles', ChoiceType::class, array(
                    'choices_as_values' => true,
                    'required' => false,
                    'choices' => array(
                        /** @Ignore */ 'User' => 'ROLE_USER',
                        /** @Ignore */ 'Librarian' => 'ROLE_LIBRARIAN',
                        /** @Ignore */ 'Admin' => 'ROLE_ADMIN',
                        /** @Ignore */ 'Network Admin' => 'ROLE_SUPER_ADMIN',
                    ),
                ))
            ->add('country', EntityType::class, array(
                'class' => 'Celsius3CoreBundle:Country',
                'mapped' => true,
                'placeholder' => '',
                'required' => false,
                'attr' => array(
                    'class' => 'country-select',
                ),
                'auto_initialize' => false,
            ))
            ->add('city', EntityType::class, array(
                'class' => 'Celsius3CoreBundle:City',
                'choices' => [],
                'mapped' => true,
                'placeholder' => '',
                'required' => false,
                'attr' => array(
                    'class' => 'city-select',
                ),
                'auto_initialize' => false,
            ))
            ->add('institution', EntityType::class, array(
                'class' => 'Celsius3CoreBundle:Institution',
                'choices' => [],
                'mapped' => true,
                'label' => ucfirst('institution'),
                'placeholder' => '',
                'required' => false,
                'attr' => array(
                    'class' => 'institution-select',
                ),
                'auto_initialize' => false,
            ));


        $builder->get('country')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $country = $event->getForm()->getData();
                $form = $event->getForm()->getParent();

                $cities = null === $country ? array() : $country->getCities();
                $form->add('city', EntityType::class, array(
                    'class' => 'Celsius3CoreBundle:City',
                    'choices' => $cities,
                    'mapped' => true,
                    'placeholder' => '',
                    'required' => false,
                    'attr' => array(
                        'class' => 'institution-select',
                    ),
                    'auto_initialize' => false,

                ));

                $institutions = null === $country ? array() : $country->getInstitutions();
                $form->add('institution', EntityType::class, array(
                    'class' => 'Celsius3CoreBundle:Institution',
                    'choices' => $institutions,
                    'mapped' => true,
                    'label' => ucfirst('institution'),
                    'placeholder' => '',
                    'required' => false,
                    'attr' => array(
                        'class' => 'institution-select',
                    ),
                    'auto_initialize' => false,
                ));
            }
        );

        $builder->get('city')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $city = $event->getForm()->getData();
                $form = $event->getForm()->getParent();
                $form->remove('institution');

                $institutions = null === $city ? array() : $city->getInstitutions();
                $form->add('institution', EntityType::class, array(
                    'class' => 'Celsius3CoreBundle:Institution',
                    'choices' => $institutions,
                    'mapped' => true,
                    'label' => ucfirst('institution'),
                    'placeholder' => '',
                    'required' => false,
                    'attr' => array(
                        'class' => 'institution-select',
                    ),
                    'auto_initialize' => false,
                ));
            }
        );

        if (is_null($options['instance'])) {
            $builder
                    ->add('instance', EntityType::class, array(
                        'required' => false,
                        'class' => 'Celsius3CoreBundle:Instance',
                    ))
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'instance' => null,
            'allow_extra_fields' => true,
            'validation_groups' => ['base_user_filter_type']
        ));
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
