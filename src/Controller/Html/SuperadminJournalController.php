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

use Celsius3\Entity\Instance;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Celsius3\Form\Type\Filter\JournalFilterType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\JournalController;

/**
 * Location controller.
 *
 * @Route("/superadmin/journal")
 */
class SuperadminJournalController extends JournalController
{

    protected function getInstance(): Instance
    { return $this->directory; }


    /**
     * Lists all Journal entities.
     *
     * @Route("/", name="superadmin_journal")
     */
    public function index(): Response
    { return $this->baseInstanceIndex(type: JournalFilterType::class); }


    /**
     * Displays a form to create a new Journal entity.
     *
     * @Route("/new", name="superadmin_journal_new")
     */
    public function new(): Response
    { return $this->baseInstanceNew(); }

    /**
     * Creates a new Journal entity.
     *
     * @Route("/create", name="superadmin_journal_create", methods={"POST"})
     */
    public function create(): RedirectResponse|Response
    { return $this->baseInstanceCreate(); }


    /**
     * Displays a form to edit an existing Journal entity.
     *
     * @Route("/{id}/edit", name="superadmin_journal_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit(string $id): Response
    { return $this->baseInstanceEdit($id); }


    /**
     * Edits an existing Journal entity.
     *
     * @Route("/{id}/update", name="superadmin_journal_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update(string $id): Response
    { return $this->baseInstanceUpdate($id, 'superadmin_journal'); }


    /**
     * Batch actions.
     *
     * @Route("/batch", name="superadmin_journal_batch")
     *
     * @return array
     */
    public function batch(): mixed
    { return $this->baseBatch(); }


    protected function batchUnion(array $element_ids): Response
    { return $this->batchUnion($element_ids); }


    /**
     * Unifies a group of Journal entities.
     *
     * @Route("/doUnion", name="superadmin_journal_doUnion", methods={"POST"})
     */
    public function doUnion(): RedirectResponse
    {
        $request = $this->requestStack->getCurrentRequest();

        return $this->baseDoUnion(
            $request->get('element'),
            $request->get('main'),
            'superadmin_journal'
        );
    }
}
