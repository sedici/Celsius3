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

namespace Celsius3\CoreBundle\Form\Type;

use Celsius3\CoreBundle\Form\EventListener\AddCustomFieldsSubscriber;
use Celsius3\CoreBundle\Form\EventListener\AddInstitutionFieldsSubscriber;
use Celsius3\CoreBundle\Helper\InstanceHelper;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminContactType extends ContactType
{
    public function __construct(EntityManager $entityManager, InstanceHelper $instanceHelper)
    {
        parent::__construct($entityManager, $instanceHelper);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        if (array_key_exists('owning_instance', $options) && !is_null($options['owning_instance'])) {
            $builder
                ->add('owningInstance', InstanceSelectorType::class, [
                    'data' => $options['owning_instance'],
                    'attr' => [
                        'value' => $options['owning_instance']->getId(),
                        'readonly' => 'readonly',
                    ],
                ]);
        }

        $subscriber = new AddInstitutionFieldsSubscriber($builder->getFormFactory(), $this->entityManager);
        $builder->addEventSubscriber($subscriber);

        $customFieldsSubscriber = new AddCustomFieldsSubscriber('Contact', $builder->getFormFactory(), $this->entityManager, $this->instanceHelper->getSessionInstance(), true);
        $builder->addEventSubscriber($customFieldsSubscriber);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'owning_instance' => null,
            'user' => null,
        ]);
    }
}
