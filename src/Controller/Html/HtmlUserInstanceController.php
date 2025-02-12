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

namespace Celsius3\Controller\Html;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Celsius3\Controller\Core\EntityController;
use Celsius3\Entity\Instance;
use Celsius3\Form\Type\InstanceType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


/**
 * Change instance controller.
 * @Route("/user/instance/")
 */
class HtmlUserInstanceController extends EntityController
{

    public function initialize(): void
    {
        $this->setEntity(Instance::class);
        parent::initialize();
        $this->htmlRenderer->setTemplatePrefix('Instance/');
        $this->typeClassName = InstanceType::class;
        $this->setSortDefaults([
            'defaultSortFieldName' => 'e.updatedAt',
            'defaultSortDirection' => 'asc',
        ]);
    }


    /**
     * Change between intances.
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
