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

use Celsius3\Entity\Configuration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Helper\ConfigurationHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Celsius3\Controller\Base\UserController;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Celsius3\Exception\Exception;


/**
 * BaseUser controller.
 * @Route("/user")
 */
class HtmlUserController extends UserController
{

    protected $configurationRepository;

    public function initialize(): void {
        parent::initialize();

        $this->configurationRepository = $this->entityManager->getRepository(Configuration::class);
        $this->htmlRenderer->setTemplatePrefix('User/Dashboard/');
    }


    /**
     * Dashboard index.
     * @Route("/", name="user_index")
     */
    public function htmlIndex(): Response
    {
        $last_messages = $this->threadRepository->findUserLastMessages($this->getUser(), 3);

        $results_per_page_config = $this->configurationRepository->findOneBy(
            [
                'instance' => $this->instance,
                'key' => ConfigurationHelper::CONF__RESULTS_PER_PAGE,
            ]
        );

        return $this->htmlRenderer->render(
            'index',
            [
                'lastMessages' => $last_messages,
                'resultsPerPage' => $results_per_page_config->getValue(),
            ]
        );
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
            if (!array_key_exists((int) $user->getInstance()->getId(), $user->getSecondaryInstances())) {
                $user->addSecondaryInstance($user->getInstance(), $user->getRoles());
            }

            if (
                !$instance
                || !array_key_exists($id, $user->getSecondaryInstances())
            ) $this->error(Exception::ENTITY_NOT_FOUND);

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


    protected function validateAjax($target): bool
    {
        $allowed_targets = [
            'Journal',
            'BaseUser',
        ];

        return in_array($target, $allowed_targets, true);
    }


    /**
     * User Ajax request.
     * @Route("/ajax", name="user_ajax")
     */
    public function userAjax(): Response
    {
        $request = $this->requestStack->getCurrentRequest();
        return $this->restRenderer->render(
            $this->restRenderer->ajax(
                $request, [], $this->instance
            )
        );
    }
}