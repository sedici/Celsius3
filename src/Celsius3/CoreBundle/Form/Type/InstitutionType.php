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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityManager;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Form\EventListener\AddInstitutionFieldsSubscriber;
use Celsius3\CoreBundle\Manager\InstanceManager;

class InstitutionType extends AbstractType
{
    private $instance;
    private $em;

    public function __construct(EntityManager $em, Instance $instance)
    {
        $this->instance = $instance;
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name')
                ->add('abbreviation')
                ->add('website', null, array(
                    'required' => false,
                    'attr' => array(
                        'value' => 'http://'
                    ),
                ))
                ->add('address', null, array(
                    'required' => false
                ))
        ;

        $subscriber = new AddInstitutionFieldsSubscriber($builder->getFormFactory(), $this->em, 'parent', false, true, true);
        $builder->addEventSubscriber($subscriber);

        if ($this->instance->getUrl() === InstanceManager::INSTANCE__DIRECTORY) {
            $builder
                    ->add('instance', null, array(
                        'label' => 'Owning Instance',
                    ))
                    ->add('celsiusInstance', null, array(
                        'required' => false,
                        'label' => 'Celsius Instance',
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
    }

    public function getName()
    {
        return 'celsius3_corebundle_institutiontype';
    }
}
