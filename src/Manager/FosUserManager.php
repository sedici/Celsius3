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

namespace Celsius3\Manager;

use Celsius3\Entity\BaseUser;
use Celsius3\Helper\InstanceHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManager as DoctrineUserManager;
use FOS\UserBundle\Util\CanonicalFieldsUpdater;
use FOS\UserBundle\Util\PasswordUpdaterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class FosUserManager extends DoctrineUserManager
{
    private $instanceHelper;
    private $entityManager;
    private $class;

    public function __construct(
        PasswordUpdaterInterface $passwordUpdater,
        CanonicalFieldsUpdater $canonicalFieldsUpdater, 
        InstanceHelper $instanceHelper,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        $class)
    {
        parent::__construct($passwordUpdater, $canonicalFieldsUpdater);
        $this->instanceHelper = $instanceHelper;
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->class = $class;
    }

    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            $user = $this->findUserByEmail($usernameOrEmail);
        } else {
            $user = $this->findUserByUsername($usernameOrEmail);
        }

        $currentUser = ($token = $this->tokenStorage->getToken()) ? $token->getUser() : null;

        return (!is_null($user) && ($user->getInstance() === $this->instanceHelper->getSessionOrUrlInstance() || (($currentUser instanceof BaseUser) && $currentUser->hasRole(UserManager::ROLE_SUPER_ADMIN)))) ? $user : null;
    }

    public function deleteUser(\FOS\UserBundle\Model\UserInterface $user)
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function findUserBy(array $criteria)
    {
        return $this->entityManager->getRepository(BaseUser::class)->findOneBy($criteria);
    }

    public function findUsers()
    {
        return $this->entityManager->getRepository($this->getClass())->findAll();
    }

    public function getClass()
    {
        return $this->class;
    }

    public function reloadUser(\FOS\UserBundle\Model\UserInterface $user)
    {
        $this->entityManager->refresh($user);
    }

    public function updateUser(\FOS\UserBundle\Model\UserInterface $user, $andFlush = true)
    {
        $this->updateCanonicalFields($user);
        $this->updatePassword($user);

        $this->entityManager->persist($user);
        if ($andFlush) {
            $this->entityManager->flush();
        }
    }
}
