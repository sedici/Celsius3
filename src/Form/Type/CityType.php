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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Celsius3\Entity\Country;
use Celsius3\Entity\Instance;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Repository\CountryRepository;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name')
                ->add('postalCode');

        $builder->add('country', EntityType::class, [
                    'class' => Country::class,
                     'query_builder' => fn(CountryRepository $cr) => $cr->getAllOrderedByNameQB(),
                     'attr' => [
                        'class' => 'country-select',
                    ],
                ]);

        if (array_key_exists('instance', $options) && !is_null($options['instance'])) {
            if ($options['instance']->getUrl() === InstanceHelper::INSTANCE__DIRECTORY) {
                $builder->add('instance', EntityType::class, [
                    'class' => Instance::class,
                ]);
            } else {
                $builder->add('instance', InstanceSelectorType::class, [
                    'data' => $options['instance'],
                    'attr' => [
                        'value' => $options['instance']->getId(),
                        'readonly' => 'readonly',
                    ],
                ]);
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'instance' => null,
        ]);
    }
}
