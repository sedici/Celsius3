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
use Doctrine\ORM\EntityManager;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Form\EventListener\AddInstitutionFieldsSubscriber;

class AdminContactType extends ContactType
{
    private $owningInstance;
    private $em;

    public function __construct(Instance $owningInstance, EntityManager $em)
    {
        parent::__construct();
        $this->owningInstance = $owningInstance;
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
                ->add('owningInstance', 'celsius3_corebundle_instance_selector', array(
                    'data' => $this->owningInstance,
                    'attr' => array(
                        'value' => $this->owningInstance->getId(),
                        'readonly' => 'readonly',
                    ),
                ))
        ;

        $subscriber = new AddInstitutionFieldsSubscriber($builder->getFormFactory(), $this->em);
        $builder->addEventSubscriber($subscriber);
    }
}