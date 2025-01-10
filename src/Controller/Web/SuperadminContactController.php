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
use Celsius3\Form\Type\SuperadminContactType;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\ContactController;

/**
 * Contact controller.
 *
 * @Route("/superadmin/contact")
 */
class SuperadminContactController extends ContactController
{

    protected function getType(): string
    { return SuperadminContactType::class; }


    protected function listQuery(): QueryBuilder
    { return $this->repository->createQueryBuilder('e'); }


    /**
     * Lists all Contact entities.
     *
     * @Route("/", name="superadmin_contact")
     */
    public function index(): Response
    {
        $pagination = $this->paginate();

        $deleteForms = [];
        foreach ($pagination as $entity) {
            $deleteForms[$entity->getId()] = $this
                ->createDeleteForm($entity->getId())
                ->createView();
        }

        return $this->render(
            (string) $this->templatePrefix . 'index.html.twig',
            [
                'pagination' => $pagination,
                'deleteForms' => $deleteForms
            ]
        );
    }


    /**
     * Finds and displays a Contact entity.
     *
     * @Route("/{id}/show", name="superadmin_contact_show")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function show(string $id): Response
    { return $this->baseShow($id); }


    /**
     * Displays a form to create a new Contact entity.
     *
     * @Route("/new", name="superadmin_contact_new")
     */
    public function new(): Response
    { return $this->baseInstanceNew(); }


    /**
     * Creates a new Contact entity.
     *
     * @Route("/create", name="superadmin_contact_create", methods={"POST"})
     */
    public function create(): Response
    { return $this->baseInstanceCreate(route: 'superadmin_contact'); }


    /**
     * Displays a form to edit an existing Contact entity.
     *
     * @Route("/{id}/edit", name="superadmin_contact_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit(string $id): Response
    {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error('entity_not_found');

        $editForm = $this->createForm(
            options: [
                'owning_instance' => $entity->getOwningInstance(),
                'user' => $entity->getUser()
            ]
        );

        return $this->render(
            (string) $this->templatePrefix . 'edit.html.twig',
            [
                'entity' => $entity,
                'edit_form' => $editForm->createView()
            ]
        );
    }


    /**
     * Edits an existing Contact entity.
     *
     * @Route("/{id}/update", name="superadmin_contact_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update(string $id): Response
    {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error('entity_not_found');

        return $this->baseInstanceUpdate(
            $id, 'superadmin_contact',
            options: [
                'owning_instance' => $entity->getOwningInstance(),
                'user' => $entity->getUser(),
            ],
            entity: $entity
        );
    }


    /**
     * Deletes a Contact entity.
     *
     * @Route("/{id}/delete", name="superadmin_contact_delete", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function delete(string $id): RedirectResponse
    { return $this->baseDelete($id, 'superadmin_contact'); }

}
