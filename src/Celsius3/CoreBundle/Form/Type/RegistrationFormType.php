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
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Celsius3\CoreBundle\Form\EventListener\AddCustomFieldsSubscriber;
use Celsius3\CoreBundle\Form\EventListener\AddInstitutionFieldsSubscriber;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RegistrationFormType extends BaseType
{
    protected $instance;
    protected $em;

    /**
     * @param string $class The User class name
     */
    public function __construct(ContainerInterface $container, $class)
    {
        parent::__construct($class);

        $this->em = $container->get('doctrine.orm.entity_manager');
        $request = $container->get('request_stack')->getCurrentRequest();
        $this->instance = $this->em
                ->getRepository('Celsius3CoreBundle:Instance')
                ->findOneBy(array('host' => $request->getHost()));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
                ->add('name', null, array(
                    'label' => 'Name'
                ))
                ->add('surname')
                ->add('birthdate', 'birthday', array(
                    'required' => false,
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                    'attr' => array(
                        'class' => 'date'
                    ),
                ))
                ->add('address', null, array(
                    'required' => false,
                ))
                ->add('instance', 'celsius3_corebundle_instance_selector', array(
                    'data' => $this->instance,
                    'attr' => array(
                        'value' => $this->instance->getId(),
                        'readonly' => 'readonly',
                    ),
                ))
        ;
        $subscriber = new AddCustomFieldsSubscriber($builder->getFormFactory(), $this->em, $this->instance, true);
        $builder->addEventSubscriber($subscriber);
        $subscriber2 = new AddInstitutionFieldsSubscriber($builder->getFormFactory(), $this->em);
        $builder->addEventSubscriber($subscriber2);
    }

    public function getName()
    {
        return 'celsius3_corebundle_registration';
    }
}
