<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Entity\Contact;
use Celsius3\CoreBundle\Form\Type\SuperadminContactType;

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
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        $data = $this->baseIndex('Contact');
        $deleteForms = array();

        foreach ($data['pagination'] as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }

        $data['deleteForms'] = $deleteForms;

        return $data;
    }

    /**
     * Finds and displays a Contact entity.
     *
     * @Route("/{id}/show", name="superadmin_contact_show")
     * @Template()
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function showAction($id)
    {
        return $this->baseShow('Contact', $id);
    }

    /**
     * Displays a form to create a new Contact entity.
     *
     * @Route("/new", name="superadmin_contact_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('Contact', new Contact(), SuperadminContactType::class);
    }

    /**
     * Creates a new Contact entity.
     *
     * @Route("/create", name="superadmin_contact_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminContact:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Contact', new Contact(), SuperadminContactType::class, array(), 'superadmin_contact');
    }

    /**
     * Displays a form to edit an existing Contact entity.
     *
     * @Route("/{id}/edit", name="superadmin_contact_edit")
     * @Template()
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function editAction($id)
    {
        $entity = $this->findQuery('Contact', $id);
        if (!$entity) {
            $this->createNotFoundException();
        }

        return $this->baseEdit('Contact', $id, SuperadminContactType::class, array(
            'owning_instance' => $entity->getOwningInstance(),
            'user' => $entity->getUser(),
        ));
    }

    /**
     * Edits an existing Contact entity.
     *
     * @Route("/{id}/update", name="superadmin_contact_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminContact:edit.html.twig")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function updateAction($id)
    {
        $entity = $this->findQuery('Contact', $id);
        if (!$entity) {
            $this->createNotFoundException();
        }

        return $this->baseUpdate('Contact', $id, SuperadminContactType::class, array(
            'owning_instance' => $entity->getOwningInstance(),
            'user' => $entity->getUser(),
        ), 'superadmin_contact');
    }

    /**
     * Deletes a Contact entity.
     *
     * @Route("/{id}/delete", name="superadmin_contact_delete")
     * @Method("post")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function deleteAction($id)
    {
        return $this->baseDelete('Contact', $id, 'superadmin_contact');
    }
}
