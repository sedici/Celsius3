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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Entity\ContactType;
use Celsius3\Form\Type\ContactTypeType;
use Symfony\Component\HttpFoundation\Response;

/**
 * ContactType controller.
 *
 * @Route("/superadmin/contacttype")
 */
class SuperadminContactTypeController extends BaseController
{
    protected function getSortDefaults()
    {
        return array(
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        );
    }

    /**
     * Lists all ContactType entities.
     *
     * @Route("/", name="superadmin_contacttype")
     */
    public function index(): Response
    {
        return $this->render(
            'Superadmin/ContactType/index.html.twig',
            $this->baseIndex('ContactType')
        );
    }

    /**
     * Displays a form to create a new ContactType entity.
     *
     * @Route("/new", name="superadmin_contacttype_new")
     */
    public function new(): Response
    {
        return $this->render(
            'Superadmin/ContactType/new.html.twig',
            $this->baseNew('ContactType', new ContactType(), ContactTypeType::class)
        );
    }

    /**
     * Creates a new ContactType entity.
     *
     * @Route("/create", name="superadmin_contacttype_create", methods={"POST"})
     */
    public function create()
    {
        return $this->render('Superadmin/Contact/new.html.twig', $this->baseCreate('ContactType', new ContactType(), ContactTypeType::class, array(), 'superadmin_contacttype'));
    }

    /**
     * Displays a form to edit an existing ContactType entity.
     *
     * @Route("/{id}/edit", name="superadmin_contacttype_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id): Response
    {
        return $this->render(
            'Superadmin/ContactType/edit.html.twig',
            $this->baseEdit('ContactType', $id, ContactTypeType::class)
        );
    }

    /**
     * Edits an existing ContactType entity.
     *
     * @Route("/{id}/update", name="superadmin_contacttype_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update($id)
    {
        return $this->render('Superadmin/ContactType/edit.html.twig', $this->baseUpdate('ContactType', $id, ContactTypeType::class, array(), 'superadmin_contacttype'));
    }
}
