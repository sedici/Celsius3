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
use Celsius3\CoreBundle\Entity\Institution;
use Celsius3\CoreBundle\Form\Type\InstitutionType;
use Celsius3\CoreBundle\Filter\Type\InstitutionFilterType;

/**
 * Location controller.
 *
 * @Route("/admin/institution")
 */
class AdminInstitutionController extends BaseInstanceDependentController
{
    protected function getSortDefaults()
    {
        return array(
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        );
    }

    /**
     * Lists all Institution entities.
     *
     * @Route("/", name="admin_institution")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('Institution', $this->createForm(new InstitutionFilterType($this->getInstance())));
    }

    /**
     * Displays a form to create a new Institution entity.
     *
     * @Route("/new", name="admin_institution_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('Institution', new Institution(), new InstitutionType($this->getDoctrine()->getManager(), $this->getInstance()));
    }

    /**
     * Creates a new Institution entity.
     *
     * @Route("/create", name="admin_institution_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminInstitution:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Institution', new Institution(), new InstitutionType($this->getDoctrine()->getManager(), $this->getInstance()), 'admin_institution');
    }

    /**
     * Displays a form to edit an existing Institution entity.
     *
     * @Route("/{id}/edit", name="admin_institution_edit")
     * @Template()
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('Institution', $id, new InstitutionType($this->getDoctrine()->getManager(), $this->getInstance()));
    }

    /**
     * Edits an existing Institution entity.
     *
     * @Route("/{id}/update", name="admin_institution_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminInstitution:edit.html.twig")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('Institution', $id, new InstitutionType($this->getDoctrine()->getManager(), $this->getInstance()), 'admin_institution');
    }
}
