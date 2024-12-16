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

namespace Celsius3\Controller;

use Celsius3\Entity\Instance;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Institution controller.
 *
 * @Route("/admin/institution")
 */
class AdminInstitutionController extends InstitutionController
{

    protected function getDirectory(): Instance|null
    {
        return $this->repository
            ->findOneBy(['url' => 'directory']);
    }


    /**
     * Lists all Institution entities.
     *
     * @Route("/", name="admin_institution")
     */
    public function index(): Response
    { return $this->baseInstanceIndex(); }


    /**
     * Displays a form to create a new Institution entity.
     *
     * @Route("/new", name="admin_institution_new", options={"expose"=true})
     */
    public function new(): Response
    { return $this->baseInstanceNew(options: [ 'show_city' => true ]); }


    /**
     * Creates a new Institution entity.
     *
     * @Route("/create", name="admin_institution_create", methods={"POST"})
     */
    public function create(): Response
    {
        return $this->baseInstanceCreate(
            options: [ 'show_city' => true ],
            route: 'admin_institution'
        );
    }


    /**
     * Displays a form to edit an existing Institution entity.
     *
     * @Route("/{id}/edit", name="admin_institution_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit(string $id): Response
    {
        return $this->baseInstanceEdit(
            $id, options: [ 'show_city' => true ]
        );
    }

    /**
     * Edits an existing Institution entity.
     *
     * @Route("/{id}/update", name="admin_institution_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update(string $id): RedirectResponse|Response
    {
        return $this->baseInstanceUpdate(
            $id, 'admin_institution',
            options: [ 'show_city' => true ]
        );
    }


    /**
     * Displays a form to edit an existing Institution entity.
     *
     * @Route("/{id}/show", name="admin_institution_show")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function show(string $id): Response
    {
        $entity = $this->findQuery($id);

        // Primero verifica que el usuario tenga acceso (ademas de que exista la entidad)
        // pero si no existe la entidad o no tiene acceso manda que no tiene acceso para brindar menos información
        if (
            $entity !== null
            && $entity->instance !== $this->getDirectory()
            && $entity->instance !== $this->instance
        ) $this->error('access_denied', msg: '');

        return $this->baseShow($id);
    }
}
