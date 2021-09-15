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

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManager;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Form\EventListener\AddInstitutionFieldsSubscriber;
use Celsius3\CoreBundle\Manager\InstanceManager;

class InstitutionType extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
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
                        'placeholder' => 'http://'
                    ),
                ))
                ->add('address', null, array(
                    'required' => false
                ))
        ;

        $subscriber = new AddInstitutionFieldsSubscriber($builder->getFormFactory(), $this->em, 'parent', false, true, true, false, $options['show_city']);
        $builder->addEventSubscriber($subscriber);

        if (array_key_exists('instance', $options) && !is_null($options['instance'])) {
            if ($options['instance']->getUrl() === InstanceManager::INSTANCE__DIRECTORY) {
                $builder
                        ->add('instance', EntityType::class, array(
                            'class' => Instance::class,
                            'label' => 'Owning Instance',
                        ))
                        ->add('celsiusInstance', EntityType::class, array(
                            'class' => Instance::class,
                            'required' => false,
                            'label' => 'Celsius Instance',
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
            'instance' => null,
            'show_city' => false
        ));
    }
}
