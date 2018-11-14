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

use Celsius3\CoreBundle\Manager\MaterialTypeManager;
use JMS\TranslationBundle\Annotation\Ignore;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $class = explode('\\', $options['material']);
        $preferredMaterial = lcfirst(str_replace('Type', '', end($class)));

        if ($preferredMaterial === 'journal') {
            $materialOptions = array(
               'constraints' => new Valid(),
               'journal' => $options['journal'],
               'other' => $options['other'],
           );
        } else {
            $materialOptions = array(
              'constraints' => new Valid(),
          );
        }

        $builder
                ->add('originalRequest', RequestType::class, array(
                    'label' => false,
                    'instance' => $options['instance'],
                    'user' => $options['user'],
                    'operator' => $options['operator'],
                    'librarian' => $options['librarian'],
                    'create' => $options['create'],
                    'target' => $options['target']
                ))
                ->add('materialDataType', ChoiceType::class, array(
                    'choices' => array(
                        /** @Ignore */ ucfirst(MaterialTypeManager::TYPE__JOURNAL) => MaterialTypeManager::TYPE__JOURNAL,
                        /** @Ignore */ ucfirst(MaterialTypeManager::TYPE__BOOK) => MaterialTypeManager::TYPE__BOOK,
                        /** @Ignore */ ucfirst(MaterialTypeManager::TYPE__CONGRESS) => MaterialTypeManager::TYPE__CONGRESS,
                        /** @Ignore */ ucfirst(MaterialTypeManager::TYPE__THESIS) => MaterialTypeManager::TYPE__THESIS,
                        /** @Ignore */ ucfirst(MaterialTypeManager::TYPE__PATENT) => MaterialTypeManager::TYPE__PATENT,
                    ),
                    'choices_as_values' => true,
                    'mapped' => false,
                    'data' => $preferredMaterial,
                    'label' => 'Material Type',
                ))
                ->add('materialData', $options['material'], $materialOptions);

        if (array_key_exists('actual_user', $options) && !is_null($options['actual_user'])) {
            if ($options['actual_user']->hasRole('ROLE_ADMIN') || $options['actual_user']->hasRole('ROLE_SUPER_ADMIN')) {
                $builder->add('save_and_show', SubmitType::class, array(
                    'attr' => array(
                        'class' => 'btn btn-primary submit-button pull-left',
                        
                        
                    ),
                    'label' => 'save_and_show',
                ));
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Celsius3\\CoreBundle\\Entity\\Order',
            'instance' => null,
            'material' => JournalTypeType::class,
            'user' => null,
            'operator' => null,
            'librarian' => false,
            'actual_user' => null,
            'journal' => null,
            'other' => '',
            'create' => false,
            'target' => ''
        ));
    }
}
