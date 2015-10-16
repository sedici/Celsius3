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
use Celsius3\CoreBundle\Entity\Hive;
use Celsius3\CoreBundle\Form\Type\HiveType;

/**
 * Hive controller.
 *
 * @Route("/superadmin/hive")
 */
class SuperadminHiveController extends BaseController
{
    protected function getSortDefaults()
    {
        return array(
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        );
    }

    /**
     * Lists all Hive entities.
     *
     * @Route("/", name="superadmin_hive")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('Hive');
    }

    /**
     * Displays a form to create a new Hive entity.
     *
     * @Route("/new", name="superadmin_hive_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('Hive', new Hive(), new HiveType());
    }

    /**
     * Creates a new Hive entity.
     *
     * @Route("/create", name="superadmin_hive_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminHive:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Hive', new Hive(), new HiveType(), 'superadmin_hive');
    }

    /**
     * Displays a form to edit an existing Hive entity.
     *
     * @Route("/{id}/edit", name="superadmin_hive_edit")
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
        return $this->baseEdit('Hive', $id, new HiveType());
    }

    /**
     * Edits an existing Hive entity.
     *
     * @Route("/{id}/update", name="superadmin_hive_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminHive:edit.html.twig")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('Hive', $id, new HiveType(), 'superadmin_hive');
    }
}
