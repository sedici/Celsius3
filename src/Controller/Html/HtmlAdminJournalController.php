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

namespace Celsius3\Controller\Html;

use Celsius3\Manager\CatalogManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\JournalController;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Location controller.
 * @Route("/admin/journal")
 */
class HtmlAdminJournalController extends JournalController
{

    public function findQuery(
        string $id,
        ?bool $isInstanceDependent = null
    ): mixed {
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
     * @Route("/", name="admin_journal")
     */
    public function htmlIndex(): Response
    {
        return $this->htmlRenderer->render(
            templateName: 'index',
            params: $this->index()
        );
    }


    /**
     * Displays data for a Journal.
     * @Route("/{id}/show", name="admin_journal_show", options={"expose"=true})
     */
    public function htmlShow($id): Response
    {
        $entity = $this->findShowQuery($id);
        if (!$entity) $this->error('entity_not_found');

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

        return $this->htmlRenderer->render(
            templateName: 'show',
            params: [
                'entity' => $entity,
                'searches' => $searches,
                'receptions' => $receptions,
            ]
        );
    }


    /**
     * Displays a form to create a new Journal entity.
     * @Route("/new", name="admin_journal_new", options={"expose"=true})
     */
    public function htmlNew(): Response
    {
        return $this->htmlRenderer->render(
            templateName: 'new',
            params: $this->new()
        );
    }


    /**
     * Creates a new Journal entity.
     * @Route("/create", name="admin_journal_create", methods={"POST"})
     *
     */
    public function htmlCreate(): RedirectResponse|Response
    {
        return $this->htmlRenderer->render(
            templateName: 'create',
            params: $this->create()
        );
    }


    /**
     * Displays a form to edit an existing Journal entity.
     * @Route("/{id}/edit", name="admin_journal_edit")
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlEdit(string $id): Response
    {
        return $this->htmlRenderer->render(
            templateName: 'edit',
            params: $this->edit($id)
        );
    }


    /**
     * Edits an existing Journal entity.
     * @Route("/{id}/update", name="admin_journal_update", methods={"POST"})
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlUpdate(string $id): RedirectResponse|Response
    {
        return $this->htmlRenderer->render(
            templateName: 'update',
            params: $this->update($id)
        );
    }
}
