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
use Celsius3\CoreBundle\Entity\LegacyInstance;
use Celsius3\CoreBundle\Form\Type\LegacyInstanceType;
use Celsius3\CoreBundle\Filter\Type\InstanceFilterType;

/**
 * Instance controller.
 *
 * @Route("/superadmin/instance/legacy")
 */
class SuperadminLegacyInstanceController extends InstanceController
{

    /**
     * Lists all Instance entities.
     *
     * @Route("/", name="superadmin_instance_legacy")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('LegacyInstance', $this->createForm(new InstanceFilterType()));
    }

    /**
     * Displays a form to create a new LegacyInstance entity.
     *
     * @Route("/new", name="superadmin_instance_legacy_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('LegacyInstance', new LegacyInstance(), new LegacyInstanceType());
    }

    /**
     * Creates a new LegacyInstance entity.
     *
     * @Route("/create", name="superadmin_instance_legacy_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminInstance:legacyNew.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('LegacyInstance', new LegacyInstance(), new LegacyInstanceType(), 'superadmin_instance_legacy');
    }

    /**
     * Displays a form to edit an existing LegacyInstance entity.
     *
     * @Route("/{id}/edit", name="superadmin_instance_legacy_edit")
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
        return $this->baseEdit('LegacyInstance', $id, new LegacyInstanceType());
    }

    /**
     * Edits an existing Instance entity.
     *
     * @Route("/{id}/update", name="superadmin_instance_legacy_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminInstance:legacyEdit.html.twig")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('LegacyInstance', $id, new LegacyInstanceType(), 'superadmin_instance_legacy');
    }
}
