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

use Celsius3\Entity\Instance;
use Celsius3\Form\EventListener\AddInstitutionFieldsSubscriber;
use Celsius3\Helper\InstanceHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SuperadminContactType extends ContactType
{
    public function __construct(EntityManagerInterface $entityManager, InstanceHelper $instanceHelper)
    {
        parent::__construct($entityManager, $instanceHelper);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('owningInstance', EntityType::class, [
                'class' => Instance::class,
                'data' => $options['owning_instance'],
                'attr' => [
                    'value' => (!is_null($options['owning_instance'])) ? $options['owning_instance']->getId() : '',
                ],
            ]);

        $subscriber = new AddInstitutionFieldsSubscriber($builder->getFormFactory(), $this->entityManager);
        $builder->addEventSubscriber($subscriber);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user' => null,
            'owning_instance' => null,
        ]);
    }
}
