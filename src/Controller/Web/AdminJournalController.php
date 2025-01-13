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

namespace Celsius3\Controller\Web;

use Celsius3\Exception\Exception;
use Celsius3\Manager\CatalogManager;
use Celsius3\Repository\EventRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Celsius3\Controller\Base\JournalController;

use Celsius3\Helper\ConfigurationHelper;
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

/**
 * Location controller.
 *
 * @Route("/admin/journal")
 */
class AdminJournalController extends JournalController
{

    private EventRepository $eventRepository;
    private Security $security;


    public function __construct(
        EventRepository $eventRepository,
        Security $security,
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

        $this->eventRepository = $eventRepository;
        $this->security = $security;
    }


    protected function findQuery($id)
    {
        $isAdmin = $this->userManager
            ->getCurrentRole($this->security->getUser()) === 'ROLE_SUPER_ADMIN';

        return $this->entityManager
            ->getRepository($this->entityClassName)
            ->findQuery($this->instance, $id, $isAdmin);
    }


    protected function findShowQuery(string $id)
    {
        return $this->repository
            ->findOneForInstanceOrGlobal(
                $this->instance,
                $this->directory,
                $id
            );
    }


    /**
     * Lists all Journal entities.
     *
     * @Route("/", name="admin_journal")
     */
    public function index(): Response
    {
        return $this->baseInstanceIndex();
    }


    /**
     * Displays data for a Journal.
     *
     * @Route("/{id}/show", name="admin_journal_show", options={"expose"=true})
     */
    public function show($id): Response
    {
        $entity = $this->findShowQuery($id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.journal');
        }
        $receptions = $this->eventRepository
            ->getPreviousJournalReceivedRequests(
                $this->instance, $entity
            );

        $results = $this->eventRepository
            ->getPreviousJournalSearches(
                $this->instance, $entity
            );

        $searches = [
            CatalogManager::CATALOG__FOUND => [],
            CatalogManager::CATALOG__PARTIALLY_FOUND => [],
        ];

        foreach ($results as $search) {
            $searches[$search->getResult()][] = $search;
        }

        return $this->render(
            $this->templatePrefix . 'show.html.twig',
            [
                'entity' => $entity,
                'searches' => $searches,
                'receptions' => $receptions,
            ]
        );
    }


    /**
     * Displays a form to create a new Journal entity.
     *
     * @Route("/new", name="admin_journal_new", options={"expose"=true})
     */
    public function new(): Response
    {
        return $this->baseInstanceNew();
    }


    /**
     * Creates a new Journal entity.
     *
     * @Route("/create", name="admin_journal_create", methods={"POST"})
     *
     */
    public function create(): RedirectResponse|Response
    {
        return $this->baseInstanceCreate();
    }


    /**
     * Displays a form to edit an existing Journal entity.
     *
     * @Route("/{id}/edit", name="admin_journal_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit(string $id): Response
    {
        return $this->baseInstanceEdit($id);
    }


    /**
     * Edits an existing Journal entity.
     *
     * @Route("/{id}/update", name="admin_journal_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update(string $id): RedirectResponse|Response
    {
        return $this->baseInstanceUpdate($id, 'admin_journal_edit');
    }
}
