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
use Celsius3\CoreBundle\Entity\Hive;
use Celsius3\Form\Type\HiveType;
use Symfony\Component\HttpFoundation\Response;

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
     */
    public function index(): Response
    {
        return $this->render('Superadmin/Hive/index.html.twig', $this->baseIndex('Hive'));
    }

    /**
     * Displays a form to create a new Hive entity.
     *
     * @Route("/new", name="superadmin_hive_new")
     */
    public function new(): Response
    {
        return $this->render(
            'Superadmin/Hive/new.html.twig',
            $this->baseNew('Hive', new Hive(), HiveType::class)
        );
    }

    /**
     * Creates a new Hive entity.
     *
     * @Route("/create", name="superadmin_hive_create", methods={"POST"})
     */
    public function create()
    {
        return $this->render('Superadmin/Hive/new.html.twig', $this->baseCreate('Hive', new Hive(), HiveType::class, array(), 'superadmin_hive'));
    }

    /**
     * Displays a form to edit an existing Hive entity.
     *
     * @Route("/{id}/edit", name="superadmin_hive_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id): Response
    {
        return $this->render('Superadmin/Hive/edit.html.twig', $this->baseEdit('Hive', $id, HiveType::class));
    }

    /**
     * Edits an existing Hive entity.
     *
     * @Route("/{id}/update", name="superadmin_hive_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update($id)
    {
        return $this->render('Superadmin/Hive/edit.html.twig', $this->baseUpdate('Hive', $id, HiveType::class, array(), 'superadmin_hive'));
    }
}
