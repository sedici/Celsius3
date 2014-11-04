<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

namespace Celsius3\MessageBundle\FormType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use FOS\MessageBundle\FormType\NewThreadMultipleMessageFormType as BaseNewThreadMultipleMessageFormType;
use Celsius3\CoreBundle\Manager\UserManager;

class NewThreadMultipleMessageFormType extends BaseNewThreadMultipleMessageFormType
{
    private $context;
    private $em;

    public function __construct(SecurityContextInterface $context, EntityManager $em)
    {
        $this->context = $context;
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isAdmin = $this->context->isGranted('ROLE_ADMIN');
        $user = $this->context->getToken()->getUser();
        if ($isAdmin) {
            $builder
                    ->add('recipients', 'celsius3_messagebundle_recipients_selector_custom', array(
                        'class' => 'Celsius3\\CoreBundle\\Entity\\BaseUser',
                        'property' => 'username',
                        'multiple' => true,
                        'query_builder' => function (EntityRepository $er) use ($user) {
                            return $er->createQueryBuilder('u')
                                    ->where('u.id <> :id')
                                    ->setParameter('id', $user->getId())
                                    ->orderBy('u.username', 'asc');
                        },
                    ))
            ;
        } else {
            $usernames = $this->em->getRepository('Celsius3CoreBundle:BaseUser')
                    ->createQueryBuilder('u')
                    ->where('u.id <> :id')
                    ->andWhere('u.instance = :instance_id')
                    ->andWhere('u.roles LIKE :role')
                    ->setParameter('id', $user->getId())
                    ->setParameter('instance_id', $user->getInstance()->getId())
                    ->setParameter('role', '%' . UserManager::ROLE_ADMIN . '%')
                    ->getQuery()
                    ->getResult();

            $builder
                    ->add('recipients', 'celsius3_messagebundle_recipients_selector_hidden', array(
                        'data' => $usernames,
                    ))
            ;
        }

        $builder
                ->add('subject', 'text')
                ->add('body', 'textarea', array(
                    'attr' => array(
                        'class' => 'summernote'
                    ),
                ))
        ;
    }
}