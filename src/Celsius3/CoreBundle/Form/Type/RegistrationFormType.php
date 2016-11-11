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
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Celsius3\CoreBundle\Form\EventListener\AddCustomFieldsSubscriber;
use Celsius3\CoreBundle\Form\EventListener\AddInstitutionFieldsSubscriber;
use Celsius3\CoreBundle\Helper\InstanceHelper;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    protected $em;
    protected $instance_helper;

    /**
     * @param string $class The User class name
     */
    public function __construct(EntityManager $em, InstanceHelper $instance_helper, $class)
    {
        $this->em = $em;
        $this->instance_helper = $instance_helper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', null, array(
                    'label' => 'Name',
                ))
                ->add('surname')
                ->add('birthdate', BirthdayType::class, array(
                    'required' => false,
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                    'attr' => array(
                        'class' => 'date',
                    ),
                ))
                ->add('address', null, array(
                    'required' => false,
                ))
                ->add('instance', InstanceSelectorType::class, array(
                    'data' => $this->instance_helper->getSessionOrUrlInstance(),
                    'attr' => array(
                        'value' => $this->instance_helper->getSessionOrUrlInstance()->getId(),
                        'readonly' => 'readonly',
                    ),
                ))
        ;

        $registration = (isset($options['registration'])) ? $options['registration'] : true;
        $customFiledsSubscriber = new AddCustomFieldsSubscriber($builder->getFormFactory(), $this->em, $this->instance_helper->getSessionOrUrlInstance(), $registration);
        $builder->addEventSubscriber($customFiledsSubscriber);

        $institutionFiledsSubscriber = new AddInstitutionFieldsSubscriber($builder->getFormFactory(), $this->em);
        $builder->addEventSubscriber($institutionFiledsSubscriber);

        $builder->add('recaptcha', EWZRecaptchaType::class, array(
            'attr' => array(
                'options' => array(
                    'theme' => 'light',
                    'type' => 'image',
                    'size' => 'normal',
                ),
            ),
            'mapped' => false,
        ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'registration' => true,
        ));
    }
}
