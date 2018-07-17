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

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Form\EventListener\AddInstitutionFieldsSubscriber;
use Symfony\Component\Form\AbstractType;
class SuperadminStatisticsType extends AbstractType
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
     /*   $builder
            ->add('fecha_inicio')
            ->add('fecha_fin');

        $builder->add('usr_gestion', EntityType::class, array(
            'class' => User::class,
            'query_builder' => function (UserRepository $ur) {
                return $ur->getAllOrderedByNameQB();
            },
            'attr' => array(
                'class' => 'country-select',
            ),
        ));
*/


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'user' => null,

        ));
    }
}
