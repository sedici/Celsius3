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
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\ContactTypeController;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * ContactType controller.
 * @Route("/superadmin/contacttype")
 */
class HtmlSuperadminContactTypeController extends ContactTypeController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->htmlRenderer->setTemplatePrefix('Superadmin/ContactType/');
        $this->setInstanceDependent(false);
        $this->setInstance($this->directory);
    }


    /**
     * Lists all ContactType entities.
     * @Route("/", name="superadmin_contacttype")
     */
    public function htmlIndex(): Response
    {
        return $this->htmlRenderer->render(
            'index',
            $this->index(hasFilterForm: false)
        );
     }


    /**
     * Displays a form to create a new ContactType entity.
     * @Route("/new", name="superadmin_contacttype_new")
     */
    public function htmlNew(): Response
    {
        return $this->htmlRenderer->render(
            'new',
            $this->new()
        );
    }


    /**
     * Creates a new ContactType entity.
     * @Route("/create", name="superadmin_contacttype_create", methods={"POST"})
     */
    public function htmlCreate(): Response
    {
        return $this->htmlRenderer->render(
            '../Contact/new',
            $this->create(redirectRoute: 'superadmin_contacttype')
        );
    }


    /**
     * Displays a form to edit an existing ContactType entity.
     * @Route("/{id}/edit", name="superadmin_contacttype_edit")
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlEdit(string $id): Response
    {
        return $this->htmlRenderer->render(
            'edit',
            $this->edit($id)
        );
    }


    /**
     * Edits an existing ContactType entity.
     * @Route("/{id}/update", name="superadmin_contacttype_update", methods={"POST"})
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlUpdate(string $id): Response
    {
        return $this->htmlRenderer->render(
            'edit',
            $this->update($id, 'superadmin_contacttype_edit')
        );
    }
}
