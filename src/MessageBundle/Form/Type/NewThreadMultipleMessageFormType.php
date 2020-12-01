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

namespace Celsius3\MessageBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Doctrine\ORM\EntityManager;
use FOS\MessageBundle\FormType\NewThreadMultipleMessageFormType as BaseNewThreadMultipleMessageFormType;
use Celsius3\CoreBundle\Manager\UserManager;
use Celsius3\CoreBundle\Form\Type\UsersSelectorType;
use Doctrine\Common\Collections\ArrayCollection;

class NewThreadMultipleMessageFormType extends BaseNewThreadMultipleMessageFormType
{
    private $authorization_checker;
    private $token_storage;
    private $em;

    public function __construct(AuthorizationChecker $authorization_checker, TokenStorage $token_storage, EntityManager $em)
    {
        $this->authorization_checker = $authorization_checker;
        $this->token_storage = $token_storage;
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isAdmin = $this->authorization_checker->isGranted('ROLE_ADMIN');
        $user = $this->token_storage->getToken()->getUser();
        if ($isAdmin) {
            $builder
                    ->add('recipients', UsersSelectorType::class, array(
                        'attr' => array(
                            'class' => 'container autocomplete_multi',
                            'target' => 'BaseUser',
                        ),
                    ))
            ;
        } else {
            $usernames = $this->em->getRepository('Celsius3CoreBundle:BaseUser')
                            ->findByUserInstanceAndRole($user, UserManager::ROLE_ADMIN);

            $builder->add('recipients', RecipientsHiddenType::class, array(
                        'data' => new ArrayCollection($usernames),
                    ));
        }

        $builder->add('subject', TextType::class)
                ->add('body', TextareaType::class, array(
                    'attr' => array(
                        'class' => 'summernote',
                    ),
                    'required' => false,
                ));
    }
}
