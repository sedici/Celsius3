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

namespace Celsius3\Controller;

use Celsius3\Controller\BaseUserController;
use Celsius3\Entity\Configuration;
use Celsius3\Entity\Thread;
use Celsius3\Repository\ThreadRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Helper\ConfigurationHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Celsius3\Helper\CustomFieldHelper;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * BaseUser controller.
 *
 * @Route("/user")
 */
final class UserController extends BaseUserController
{

    protected ThreadRepository $threadRepository;
    protected $configurationRepository;
    protected SessionInterface $session;

    public function __construct(
        SessionInterface $session,
        TokenStorageInterface $tokenStorage,
        CustomFieldHelper $customFieldHelper,
        InstanceManager $instanceManager,
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
        ConfigurationHelper $configurationHelper,
        TranslatorInterface $translator,
        ManagerRegistry $managerRegistry,
        RequestStack $requestStack,
        UnionManager $unionManager,
        UserManager $userManager,
        FilterManager $filterManager,
        InstanceHelper $instanceHelper
    ) {
        parent::__construct(
            $tokenStorage,
            $customFieldHelper,
            $instanceManager,
            $entityManager,
            $paginator,
            $configurationHelper,
            $translator,
            $managerRegistry,
            $requestStack,
            $unionManager,
            $userManager,
            $filterManager,
            $instanceHelper
        );

        $this->session = $session;
    }

    public function initialize(): void {
        $this->threadRepository = $this->entityManager
            ->getRepository(Thread::class);
        $this->configurationRepository = $this->entityManager
            ->getRepository(Configuration::class);

        parent::initialize();
    }


    final protected function getTemplatePrefix(): string
    { return 'User/Dashboard/'; }


    /**
     * Dashboard index.
     *
     * @Route("/", name="user_index")
     */
    public function index(): Response
    {
        $last_messages = $this->threadRepository->findUserLastMessages($this->getUser(), 3);

        // throw new \Exception((string) $this->instanceHelper->getSessionOrUrlInstance());

        $results_per_page_config = $this->configurationRepository->findOneBy(
            [
                'instance' => $this->instance,
                'key' => ConfigurationHelper::CONF__RESULTS_PER_PAGE,
            ]
        );

        return $this->render(
            (string) $this->templatePrefix . 'index.html.twig',
            [
                'lastMessages' => $last_messages,
                'resultsPerPage' => $results_per_page_config->getValue(),
            ]
        );
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