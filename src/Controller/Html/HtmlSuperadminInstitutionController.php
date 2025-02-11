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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Celsius3\Controller\Base\InstitutionController;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Location controller.
 *
 * @Route("/superadmin/institution")
 */
class HtmlSuperadminInstitutionController extends InstitutionController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->setInstanceDependent(false);
        $this->setInstance($this->directory);
    }


    /**
     * Lists all Institution entities.
     * @Route("/", name="superadmin_institution")
     */
    public function htmlIndex(): Response
    {
        return $this->htmlRenderer->render(
            templateName: 'index',
            params: $this->index(isInstanceDependent: true)
        );
    }


    /**
     * Displays a form to create a new Institution entity.
     * @Route("/new", name="superadmin_institution_new")
     */
    public function htmlNew(): Response
    {
        return $this->htmlRenderer->render(
            templateName: 'new',
            params: $this->new()
        );
    }


    /**
     * Creates a new Institution entity.
     * @Route("/create", name="superadmin_institution_create", methods={"POST"})
     */
    public function htmlCreate(): RedirectResponse|Response
    {
        return $this->htmlRenderer->render(
            templateName: 'create',
            params: $this->create()
        );
    }


    /**
     * Displays a form to edit an existing Institution entity.
     * @Route("/{id}/edit", name="superadmin_institution_edit")
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
     * Edits an existing Institution entity.
     * @Route("/{id}/update", name="superadmin_institution_update", methods={"POST"})
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlUpdate(string $id): Response
    {
        return $this->htmlRenderer->render(
            templateName: 'edit',
            params: $this->update($id)
        );
    }


    /**
     * Batch actions.
     * @Route("/batch", name="superadmin_institution_batch")
     * @return array
     */
    public function batch()
    { return $this->baseBatch(); }


    protected function batchUnion(array $element_ids): Response
    {
        return $this->htmlRenderer->render(
            templateName: 'batchUnion',
            params: $this->baseUnion($element_ids)
        );
    }


    /**
     * Unifies a group of Institution entities.
     * @Route("/doUnion", name="superadmin_institution_doUnion", methods={"POST"})
     */
    public function doUnion(): RedirectResponse
    {
        $request = $this->requestStack->getCurrentRequest();
        $element_ids = $request->get('element');
        $main_id = $request->get('main');

        $this->baseDoUnion(
            $element_ids,
            $main_id
        );

        return $this->redirectToRoute('superadmin_institution');
    }


    /**
     * Displays a form to edit an existing Institution entity.
     * @Route("/{id}/show", name="superadmin_institution_show")
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlShow(string $id): Response
    {
        return $this->htmlRenderer->render(
            templateName: 'show',
            params: $this->show($id)
        );
    }
}
