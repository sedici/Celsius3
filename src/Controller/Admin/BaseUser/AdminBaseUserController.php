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

namespace Celsius3\Controller\Admin\BaseUser;

use Celsius3\Controller\BaseUserController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

final class AdminBaseUserController extends BaseUserController
{

    protected final function getTemplatePrefix(): string
    { return 'Admin/BaseUser/'; }


    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.surname',
            'defaultSortDirection' => 'asc',
        ];
    }


    /**
     * Lists all BaseUser entities.
     *
     * @Route("/", name="admin_baseuser")
     */
    public function index(): Response
    {
        return $this->baseInstanceIndex();
    }


    /**
     * Finds and displays a BaseUser document.
     *
     * @Route("/{id}/show", name="admin_baseuser_show")
     *
     * @param string $id The document ID
     *
     * @throws NotFoundHttpException If document doesn't exists
     */
    public function show(string $id)
    {
        $this->baseShow($id);
    }


    /**
     * Displays a form to create a new BaseUser entity.
     *
     * @Route("/new", name="admin_baseuser_new")
     */
    public function new(): Response
    {
        return $this->baseInstanceNew(options: ['validation_groups' => 'Registration']);
    }


    /**
     * Creates a new BaseUser entity.
     *
     * @Route("/create", name="admin_baseuser_create", methods={"POST"})
     */
    public function create(): RedirectResponse|Response
    {
        return $this->baseInstanceCreate(route: 'admin_baseuser');
    }


    /**
     * Displays a form to edit an existing Country entity.
     *
     * @Route("/{id}/edit", name="admin_baseuser_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit(string $id): Response
    {
        return $this->baseInstanceEdit($id, options: [ 'editing' => true ]);
    }


    /**
     * Edits an existing BaseUser entity.
     *
     * @Route("/{id}/update", name="admin_baseuser_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update($id): RedirectResponse|Response
    {
        return $this->baseInstanceUpdate($id, 'admin_baseuser');
    }


    // COMPLETAR --
    // REVISAR MEJOR LOS PROCESAMIENTOS BATCH Y UNION
    // COMPLETAR --


    /**
     * Apply a batch function to a group of BaseUser entities.
     *
     * @Route("/batch", name="admin_baseuser_batch")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function batch(): mixed
    {
        return $this->baseBatch();
    }


    protected function batchEnable($element_ids): RedirectResponse
    {
        return $this->baseBatchEnable($element_ids);
    }


    protected function batchUnion($element_ids): Response
    {
        return $this->render(
            (string) $this->templatePrefix . 'batchUnion.html.twig',
            $this->baseUnion($element_ids)
        );
    }
}