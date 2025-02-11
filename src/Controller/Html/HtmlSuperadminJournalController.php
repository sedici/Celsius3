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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\JournalController;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Location controller.
 * @Route("/superadmin/journal")
 */
class HtmlSuperadminJournalController extends JournalController
{
    
    public function initialize(): void
    {
        parent::initialize();
        $this->setInstanceDependent(false);
        $this->setInstance($this->directory);
    }


    /**
     * Lists all Journal entities.
     * @Route("/", name="superadmin_journal")
     */
    public function htmlIndex(): Response
    {
        return $this->htmlRenderer->render(
            templateName: 'index',
            params: $this->index()
        );
    }


    /**
     * Displays a form to create a new Journal entity.
     * @Route("/new", name="superadmin_journal_new")
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
     * @Route("/create", name="superadmin_journal_create", methods={"POST"})
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
     * @Route("/{id}/edit", name="superadmin_journal_edit")
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
     * @Route("/{id}/update", name="superadmin_journal_update", methods={"POST"})
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlUpdate(string $id): Response
    {
        return $this->htmlRenderer->render(
            templateName: 'update',
            params: $this->update($id)
        );
    }


    /**
     * Batch actions.
     * @Route("/batch", name="superadmin_journal_batch")
     * @return array
     */
    public function batch(): mixed
    { return $this->baseBatch(); }


    protected function batchUnion(array $element_ids): Response
    { return $this->batchUnion($element_ids); }


    /**
     * Unifies a group of Journal entities.
     * @Route("/doUnion", name="superadmin_journal_doUnion", methods={"POST"})
     */
    public function doUnion(): RedirectResponse
    {
        $request = $this->requestStack->getCurrentRequest();

        $this->baseDoUnion(
            $request->get('element'),
            $request->get('main')
        );

        return $this->redirectToRoute('superadmin_journal');
    }
}
