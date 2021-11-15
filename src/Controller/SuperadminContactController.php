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
use Celsius3\Entity\Contact;
use Celsius3\Form\Type\SuperadminContactType;
use Celsius3\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Contact controller.
 *
 * @Route("/superadmin/contact")
 */
class SuperadminContactController extends BaseController
{
    /**
     * Lists all Contact entities.
     *
     * @Route("/", name="superadmin_contact")
     */
    public function index(): Response
    {
        $data = $this->baseIndex('Contact');
        $deleteForms = [];

        foreach ($data['pagination'] as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }

        $data['deleteForms'] = $deleteForms;

        return $this->render('Superadmin/Catalog/index.html.twig', $data);
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
    public function show($id)
    {
        return $this->render(
            'Superadmin/Contact/show.html.twig',
            $this->baseShow('Contact', $id)
        );
    }

    /**
     * Displays a form to create a new Contact entity.
     *
     * @Route("/new", name="superadmin_contact_new")
     */
    public function new(): Response
    {
        return $this->render(
            'Superadmin/Contact/new.html.twig',
            $this->baseNew('Contact', new Contact(), SuperadminContactType::class)
        );
    }

    /**
     * Creates a new Contact entity.
     *
     * @Route("/create", name="superadmin_contact_create", methods={"POST"})
     */
    public function create()
    {
        return $this->render('Superadmin/Contact/new.html.twig', $this->baseCreate('Contact', new Contact(), SuperadminContactType::class, array(), 'superadmin_contact'));
    }

    /**
     * Displays a form to edit an existing Contact entity.
     *
     * @Route("/{id}/edit", name="superadmin_contact_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id): Response
    {
        $entity = $this->findQuery('Contact', $id);
        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.contact');
        }

        return $this->render(
            'Superadmin/Contact/edit.html.twig',
            $this->baseEdit('Contact', $id, SuperadminContactType::class, [
                'owning_instance' => $entity->getOwningInstance(),
                'user' => $entity->getUser(),
            ])
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
    public function update($id)
    {
        $entity = $this->findQuery('Contact', $id);
        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.contact');
        }

        return $this->render('Superadmin/Contact/edit.html.twig', $this->baseUpdate('Contact', $id, SuperadminContactType::class, array(
                    'owning_instance' => $entity->getOwningInstance(),
                    'user' => $entity->getUser(),
                        ), 'superadmin_contact'));
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
    public function delete($id)
    {
        return $this->baseDelete('Contact', $id, 'superadmin_contact');
    }

}
