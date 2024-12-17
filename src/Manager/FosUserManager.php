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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class FosUserManager
{
    private InstanceHelper $instanceHelper;
    private EntityManagerInterface $entityManager;
    private string $class;
    private Security $security;

    public function __construct(
        InstanceHelper $instanceHelper,
        EntityManagerInterface $entityManager,
        Security $security
    ) {
        $this->instanceHelper = $instanceHelper;
        $this->entityManager = $entityManager;
        $this->class = BaseUser::class;
        $this->security = $security;
    }

    public function findUserByUsernameOrEmail(string $usernameOrEmail): ?BaseUser
    {
        $user = (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL))
            ? $this->findUserBy([ 'email' => $usernameOrEmail ])
            : $this->findUserBy([ 'username' => $usernameOrEmail ]);

        // Obtener el usuario actual desde el token de seguridad
        $currentUser = ($token = $this->security->getToken()) ? $token->getUser() : null;

        // Comprobar si el usuario encontrado es válido
        return ($user !== null && 
            ($user->getInstance() === $this->instanceHelper->getSessionOrUrlInstance() || 
            (($currentUser instanceof BaseUser) && 
            in_array(UserManager::ROLE_SUPER_ADMIN, $currentUser->getRoles())))) ? 
            $user : null;
    }

    public function deleteUser(BaseUser $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function findUserBy(array $criteria): ?BaseUser
    {
        return $this->entityManager->getRepository(BaseUser::class)->findOneBy($criteria);
    }

    public function findUsers(): array
    {
        return $this->entityManager->getRepository($this->getClass())->findAll();
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function reloadUser(BaseUser $user): void
    {
        $this->entityManager->refresh($user);
    }

    public function updateUser(BaseUser $user, bool $andFlush = true): void
    {
        // Asumiendo que estos métodos están definidos en la clase base o en otra parte del código
        // Debes implementar estos métodos según tu lógica actual.
        // Ejemplo: 
        //  - updateCanonicalFields($user);
        //  - updatePassword($user);

        // Persistir el usuario actualizado
        $this->entityManager->persist($user);
        
        if ($andFlush) {
            $this->entityManager->flush();
        }
    }
}

