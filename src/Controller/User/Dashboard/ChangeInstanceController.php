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
use Celsius3\Entity\Instance;
use Celsius3\Form\Type\InstanceType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use function array_key_exists;

/**
 * Change instance controller.
 *
 * @Route("/user/instance/")
 */
final class ChangeInstanceController extends BaseInstanceDependentController
{
    private SessionInterface $session;
    private $tokenStorage;

    public function __construct(
        SessionInterface $session,
        TokenStorageInterface $tokenStorage,
        ... $args
    ) {
        parent::__construct(... $args);
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
    }


    protected final function getEntity(): string
    { return Instance::class; }

    protected final function getType(): string
    { return InstanceType::class; }

    protected final function getTemplatePrefix(): string
    { return 'Instance/'; }


    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.updatedAt',
            'defaultSortDirection' => 'asc',
        ];
    }


    /**
     * Change between intances.
     *
     * @Route("/{id}/change", name="user_change_context")
     */
    public function change(string $id): RedirectResponse
    {
        $instance = $this->findQuery($id);
        $user = $this->getUser();

        if (array_key_exists($id, $user->getSecondaryInstances()) || ($user->getInstance()->getId() === (int)$id)) {
            if (!array_key_exists($user->getInstance()->getId(), $user->getSecondaryInstances())) {
                $user->addSecondaryInstance($user->getInstance(), $user->getRoles());
            }

            if (
                !$instance
                || !array_key_exists($id, $user->getSecondaryInstances())
            ) $this->error('entity_not_found');

            $this->session->set('instance_id', $instance->getId());
            $this->session->set('instance_url', $instance->getUrl());
            $this->session->set('instance_host', $instance->getHost());

            if ($this->session->get('admin_instance')) {
                $this->session->remove('admin_instance');
            }

            if ($user->getSecondaryInstances()) {
                $user->setRoles($user->getSecondaryInstances()[$id]);
            }

            $token = new UsernamePasswordToken(
                $user,
                'main',
                $user->getRoles(),
            );
            $this->tokenStorage->setToken($token);
        }

        if ($user->hasRole('ROLE_ADMIN')) {
            return $this->redirect($this->generateUrl('administration'));
        }

        return $this->redirect($this->generateUrl('public_index'));
    }
}
