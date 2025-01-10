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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\HiveController;

/**
 * Hive controller.
 *
 * @Route("/superadmin/hive")
 */
class SuperadminHiveController extends HiveController
{

    /**
     * Lists all Hive entities.
     *
     * @Route("/", name="superadmin_hive")
     */
    public function index(): Response
    { return $this->baseIndex(hasFilterForm: false); }


    /**
     * Displays a form to create a new Hive entity.
     *
     * @Route("/new", name="superadmin_hive_new")
     */
    public function new(): Response
    { return $this->baseNew(); }


    /**
     * Creates a new Hive entity.
     *
     * @Route("/create", name="superadmin_hive_create", methods={"POST"})
     */
    public function create()
    { return $this->baseCreate(route: 'superadmin_hive'); }


    /**
     * Displays a form to edit an existing Hive entity.
     *
     * @Route("/{id}/edit", name="superadmin_hive_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id): Response
    { return $this->baseEdit($id); }


    /**
     * Edits an existing Hive entity.
     *
     * @Route("/{id}/update", name="superadmin_hive_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update($id): Response
    { return $this->baseUpdate($id, 'superadmin_hive'); }
}
