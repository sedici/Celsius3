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

declare(strict_types=1);

namespace Celsius3\Controller\User\Dashboard;

use Celsius3\Controller\BaseInstanceDependentController;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\Exception\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use function array_key_exists;

final class ChangeInstanceController extends BaseInstanceDependentController
{
    private $instanceRepository;
    private $tokenStorage;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->instanceRepository = $entityManager->getRepository(Instance::class);
        $this->tokenStorage = $tokenStorage;
    }

    public function __invoke($id): RedirectResponse
    {
        $instance = $this->instanceRepository->find($id);
        $user = $this->getUser();

        if (array_key_exists($id, $user->getSecondaryInstances()) || ($user->getInstance()->getId() === (int)$id)) {
            if (!array_key_exists($user->getInstance()->getId(), $user->getSecondaryInstances())) {
                $user->addSecondaryInstance($user->getInstance(), $user->getRoles());
            }

            if (!$instance || !array_key_exists($id, $user->getSecondaryInstances())) {
                throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.instance');
            }

            $this->get('session')->set('instance_id', $instance->getId());
            $this->get('session')->set('instance_url', $instance->getUrl());
            $this->get('session')->set('instance_host', $instance->getHost());

            if ($this->get('session')->get('admin_instance')) {
                $this->get('session')->remove('admin_instance');
            }

            if ($user->getSecondaryInstances()) {
                $user->setRoles($user->getSecondaryInstances()[$id]);
            }

            $token = new UsernamePasswordToken(
                $user,
                null,
                'main',
                $user->getRoles()
            );
            $this->tokenStorage->setToken($token);
        }

        if ($user->hasRole('ROLE_ADMIN')) {
            return $this->redirect($this->generateUrl('administration'));
        }

        return $this->redirect($this->generateUrl('public_index'));
    }
}
