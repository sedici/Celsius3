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

namespace Celsius3\CoreBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Form\EventListener\AddCustomFieldsSubscriber;
use Celsius3\CoreBundle\Helper\InstanceHelper;
use Celsius3\CoreBundle\Manager\InstanceManager;

class BaseUserType extends RegistrationFormType
{

    public function __construct(EntityManager $em, InstanceHelper $instance_helper, $class)
    {
        parent::__construct($em, $instance_helper, $class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->get('birthdate')->setAttribute('required', false);
        $builder
                ->add('enabled', null, array(
                    'required' => false,
                ))
                ->add('locked', null, array(
                    'required' => false,
                ))
                ->add('pdf', null, array(
                    'required' => false,
                ))
                ->add('downloadAuth', null, array(
                    'required' => false,
                ))
        ;
        if (array_key_exists('instance', $options) && !is_null($options['instance'])) {
            if ($options['instance']->getUrl() === InstanceManager::INSTANCE__DIRECTORY) {
                $builder
                        ->add('instance', null, array(
                            'query_builder' => function (EntityRepository $repository) {
                                return $repository->findAllExceptDirectory();
                            },
                        ))
                ;
            } else {
                $builder
                        ->add('instance', InstanceSelectorType::class, array(
                            'data' => $options['instance'],
                            'attr' => array(
                                'value' => $options['instance']->getId(),
                                'readonly' => 'readonly',
                            ),
                        ))
                ;
            }
        }

        if ($options['editing']) {
            $builder->remove('plainPassword');
        }
        if (!is_null($options['instance'])) {
            $subscriber = new AddCustomFieldsSubscriber($builder->getFormFactory(), $this->em, $options['instance'], false);
            $builder->addEventSubscriber($subscriber);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'instance' => null,
            'editing' => false,
        ));
    }
}
