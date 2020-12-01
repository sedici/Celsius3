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

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Entity\ContactType;
use Celsius3\CoreBundle\Form\Type\ContactTypeType;

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
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('ContactType');
    }

    /**
     * Displays a form to create a new ContactType entity.
     *
     * @Route("/new", name="superadmin_contacttype_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('ContactType', new ContactType(), ContactTypeType::class);
    }

    /**
     * Creates a new ContactType entity.
     *
     * @Route("/create", name="superadmin_contacttype_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminContactType:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('ContactType', new ContactType(), ContactTypeType::class, array(), 'superadmin_contacttype');
    }

    /**
     * Displays a form to edit an existing ContactType entity.
     *
     * @Route("/{id}/edit", name="superadmin_contacttype_edit")
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
        return $this->baseEdit('ContactType', $id, ContactTypeType::class);
    }

    /**
     * Edits an existing ContactType entity.
     *
     * @Route("/{id}/update", name="superadmin_contacttype_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminContactType:edit.html.twig")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('ContactType', $id, ContactTypeType::class, array(), 'superadmin_contacttype');
    }
}
