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

use Celsius3\CoreBundle\Entity\CustomField;
use Celsius3\Form\Type\CustomFieldType;
use Celsius3\Form\Type\Filter\CustomFieldFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * Order controller.
 *
 * @Route("/admin/customuserfield")
 */
class AdminCustomFieldController extends BaseInstanceDependentController
{
    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        ];
    }

    /**
     * Lists all CustomField entities.
     *
     * @Route("/", name="admin_customuserfield")
     */
    public function index()
    {
        return $this->render(
            'Admin/CustomField/index.html.twig',
            $this->baseIndex(
                'CustomField',
                $this->createForm(CustomFieldFilterType::class, null, [
                    'instance' => $this->getInstance(),
                ])
            )
        );
    }

    /**
     * Displays a form to create a new CustomField entity.
     *
     * @Route("/new", name="admin_customuserfield_new")
     */
    public function new(): Response
    {
        return $this->render('Admin/CustomField/new.html.twig',
            $this->baseNew('CustomField', new CustomField(), CustomFieldType::class, [
            'instance' => $this->getInstance(),
        ]));
    }

    /**
     * Creates a new CustomField entity.
     *
     * @Route("/create", name="admin_customuserfield_create", methods={"POST"})
     */
    public function create()
    {
        return $this->render('Admin/CustomField/new.html.twig', $this->baseCreate('CustomField', new CustomField(), CustomFieldType::class, [
            'instance' => $this->getInstance(),
        ], 'admin_customuserfield'));

    }

    /**
     * Displays a form to edit an existing CustomField entity.
     *
     * @Route("/{id}/edit", name="admin_customuserfield_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id)
    {
        return $this->render('Admin/CustomField/edit.html.twig', $this->baseEdit('CustomField', $id, CustomFieldType::class, [
            'instance' => $this->getInstance(),
        ]));
    }

    /**
     * Edits an existing CustomField entity.
     *
     * @Route("/{id}/update", name="admin_customuserfield_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update($id)
    {
        return $this->render('Admin/CustomField/edit.html.twig', $this->baseUpdate('CustomField', $id, CustomFieldType::class, [
            'instance' => $this->getInstance(),
        ], 'admin_customuserfield'));
    }
}
