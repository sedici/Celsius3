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

use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\ContactTypeController;

/**
 * ContactType controller.
 *
 * @Route("/superadmin/contacttype")
 */
class SuperadminContactTypeController extends ContactTypeController
{

    final protected function getTemplatePrefix(): string
    { return 'Superadmin/ContactType/'; }


    protected function listQuery(): QueryBuilder
    { return $this->repository->createQueryBuilder('e'); }


    /**
     * Lists all ContactType entities.
     *
     * @Route("/", name="superadmin_contacttype")
     */
    public function index(): Response
    { return $this->baseInstanceIndex(hasFilterForm: false); }


    /**
     * Displays a form to create a new ContactType entity.
     *
     * @Route("/new", name="superadmin_contacttype_new")
     */
    public function new(): Response
    { return $this->baseInstanceNew(); }


    /**
     * Creates a new ContactType entity.
     *
     * @Route("/create", name="superadmin_contacttype_create", methods={"POST"})
     */
    public function create(): Response
    { return $this->baseInstanceCreate(template: 'Superadmin/Contact/new.html.twig'); }


    /**
     * Displays a form to edit an existing ContactType entity.
     *
     * @Route("/{id}/edit", name="superadmin_contacttype_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit(string $id): Response
    { return $this->baseInstanceEdit($id); }


    /**
     * Edits an existing ContactType entity.
     *
     * @Route("/{id}/update", name="superadmin_contacttype_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update(string $id): Response
    { return $this->baseInstanceUpdate($id, 'superadmin_contacttype'); }
}
