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

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Celsius3\Manager\InstanceManager;
use Celsius3\Form\EventListener\AddInstitutionFieldsSubscriber;
use Celsius3\Form\EventListener\AddEnableCatalogFieldSubscriber;

class CatalogType extends AbstractType
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
                ->add('url', null, array(
                    'attr' => array(
                        'placeholder' => 'http://'
                    ),
                ))
                ->add('comments', TextareaType::class, array(
                    'required' => false,
                ))
        ;

        $subscriber = new AddInstitutionFieldsSubscriber($builder->getFormFactory(), $this->em, 'institution', false);
        $builder->addEventSubscriber($subscriber);

        $enableCatalogFieldSubscriber = new AddEnableCatalogFieldSubscriber($this->em, $builder->getFormFactory());
        $builder->addEventSubscriber($enableCatalogFieldSubscriber);

        if (array_key_exists('instance', $options)) {
            if ($options['instance']->getUrl() === InstanceManager::INSTANCE__DIRECTORY) {
                $builder->add('instance');
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
        ));
    }
}
