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
use Celsius3\CoreBundle\Entity\Instance;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Celsius3\CoreBundle\Form\EventListener\AddCustomFieldsSubscriber;
use Celsius3\CoreBundle\Manager\InstanceManager;
use Doctrine\ORM\EntityRepository;

class BaseUserType extends RegistrationFormType
{
    private $editing;

    public function __construct(ContainerInterface $container, $class, Instance $instance, $editing = false)
    {
        parent::__construct($container, $class);
        $this->instance = $instance;
        $this->editing = $editing;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
                ->add('enabled', null, array(
                    'required' => false,
                ))
                ->add('locked', null, array(
                    'required' => false,
                ))
        ;
        if ($this->instance->getUrl() === InstanceManager::INSTANCE__DIRECTORY) {
            $builder
                    ->add('instance', null, array(
                        'query_builder' => function (EntityRepository $repository) {
                            return $repository->findAllExceptDirectory();
                        },
                    ))
            ;
        } else {
            $builder
                    ->add('instance', 'celsius3_corebundle_instance_selector', array(
                        'data' => $this->instance,
                        'attr' => array(
                            'value' => $this->instance->getId(),
                            'readonly' => 'readonly',
                        ),
                    ))
            ;
        }

        if ($this->editing) {
            $builder->remove('plainPassword');
        }
        $subscriber = new AddCustomFieldsSubscriber($builder->getFormFactory(), $this->em, $this->instance, false);
        $builder->addEventSubscriber($subscriber);
    }

    public function getName()
    {
        return 'celsius3_corebundle_baseusertype';
    }
}
