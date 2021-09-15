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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Celsius3\CoreBundle\Manager\MailManager;

class MailTemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('title');

        if($options['super_admin']) {
            $builder
                ->add('code', ChoiceType::class, [
                    'choices' => [
                        '' => '',
                        MailManager::MAIL__ORDER_CANCEL => MailManager::MAIL__ORDER_CANCEL,
                        MailManager::MAIL__ORDER_DOWNLOAD => MailManager::MAIL__ORDER_DOWNLOAD,
                        MailManager::MAIL__ORDER_PRINTED => MailManager::MAIL__ORDER_PRINTED,
                        MailManager::MAIL__ORDER_PRINTED_RECONFIRM => MailManager::MAIL__ORDER_PRINTED_RECONFIRM,
                        MailManager::MAIL__USER_LOST => MailManager::MAIL__USER_LOST,
                        MailManager::MAIL__USER_WELCOME => MailManager::MAIL__USER_WELCOME,
                        MailManager::MAIL__USER_WELCOME_PROVISION => MailManager::MAIL__USER_WELCOME_PROVISION,
                        MailManager::MAIL__NO_HIVE => MailManager::MAIL__NO_HIVE,
                        MailManager::MAIL__RESETTING => MailManager::MAIL__RESETTING,
                        MailManager::MAIL__USER_CONFIRMATION => MailManager::MAIL__USER_CONFIRMATION,
                        MailManager::MAIL__CUSTOM => MailManager::MAIL__CUSTOM,
                    ],
                ]);
        } else {
            $builder->add('code', HiddenType::class, ['data' => MailManager::MAIL__CUSTOM]);
        }

             $builder->add('text', TextareaType::class, array(
                    'attr' => array(
                        'class' => 'summernote',
                    ),
                    'required' => false,
                ))
        ;

        if (array_key_exists('instance', $options) && !is_null($options['instance'])) {
            $builder->add('instance', InstanceSelectorType::class, array(
                'data' => $options['instance'],
                'attr' => array(
                    'value' => $options['instance']->getId(),
                    'readonly' => 'readonly',
                ),
            ));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'instance' => null,
            'code' => null,
            'super_admin' => false
        ));
    }
}
