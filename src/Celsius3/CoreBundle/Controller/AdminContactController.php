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
use Celsius3\CoreBundle\Document\Contact;
use Celsius3\CoreBundle\Form\Type\AdminContactType;

/**
 * Contact controller.
 *
 * @Route("/admin/contact")
 */
class AdminContactController extends BaseInstanceDependentController
{

    protected function listQuery($name)
    {
        return $this->getDocumentManager()
                        ->getRepository($this->getBundle() . ':' . $name)
                        ->createQueryBuilder()
                        ->field('owningInstance.id')->equals($this->getInstance()->getId());
    }

    protected function findQuery($name, $id)
    {
        return $this->getDocumentManager()
                        ->getRepository($this->getBundle() . ':' . $name)
                        ->createQueryBuilder()
                        ->field('owningInstance.id')->equals($this->getInstance()->getId())
                        ->field('id')->equals($id)
                        ->getQuery()
                        ->getSingleResult();
    }

    /**
     * Lists all Contact documents.
     *
     * @Route("/", name="admin_contact")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('Contact');
    }

    /**
     * Finds and displays a Contact document.
     *
     * @Route("/{id}/show", name="admin_contact_show")
     * @Template()
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function showAction($id)
    {
        return $this->baseShow('Contact', $id);
    }

    /**
     * Displays a form to create a new Contact document.
     *
     * @Route("/new", name="admin_contact_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('Contact', new Contact(), new AdminContactType($this->getInstance(), $this->getDocumentManager()));
    }

    /**
     * Creates a new Contact document.
     *
     * @Route("/create", name="admin_contact_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:Contact:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Contact', new Contact(), new AdminContactType($this->getInstance(), $this->getDocumentManager()), 'admin_contact');
    }

    /**
     * Displays a form to edit an existing Contact document.
     *
     * @Route("/{id}/edit", name="admin_contact_edit")
     * @Template()
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('Contact', $id, new AdminContactType($this->getInstance(), $this->getDocumentManager()));
    }

    /**
     * Edits an existing Contact document.
     *
     * @Route("/{id}/update", name="admin_contact_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:Contact:edit.html.twig")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('Contact', $id, new AdminContactType($this->getInstance(), $this->getDocumentManager()), 'admin_contact');
    }

    /**
     * Deletes a Contact document.
     *
     * @Route("/{id}/delete", name="admin_contact_delete")
     * @Method("post")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function deleteAction($id)
    {
        return $this->baseDelete('Contact', $id, 'admin_contact');
    }

}