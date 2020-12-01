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

use Celsius3\CoreBundle\Entity\ContactType as Entity;
use Celsius3\CoreBundle\Form\EventListener\AddCustomFieldsSubscriber;
use Celsius3\CoreBundle\Helper\InstanceHelper;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    protected $entityManager;
    protected $instanceHelper;

    /**
     * @param string $class The User class name
     */
    public function __construct(EntityManager $entityManager, InstanceHelper $instanceHelper)
    {
        $this->entityManager = $entityManager;
        $this->instanceHelper = $instanceHelper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('surname')
            ->add('email', null, [
                'label' => 'mail'
            ])
            ->add('address', null, [
                'required' => false
            ])
            ->add('user', UserSelectorType::class, [
                'attr' => [
                    'class' => 'container',
                    'readonly' => 'readonly',
                    'value' => (!is_null($options['user'])) ? $options['user']->getId() : '',
                ],
                'required' => false
            ])
            ->add('user_autocomplete', TextType::class, [
                'attr' => [
                    'value' => $options['user'],
                    'class' => 'autocomplete',
                    'target' => 'BaseUser',
                ],
                'mapped' => false,
                'label' => 'User',
                'required' => false
            ])
            ->add('type', EntityType::class, [
                'class' => Entity::class,
                'choice_translation_domain' => 'messages',
                'placeholder' => ' '
            ]);

        $customFiledsSubscriber = new AddCustomFieldsSubscriber('Contact', $builder->getFormFactory(), $this->entityManager, $this->instanceHelper->getSessionOrUrlInstance(), true);
        $builder->addEventSubscriber($customFiledsSubscriber);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user' => null,
        ]);
    }
}
