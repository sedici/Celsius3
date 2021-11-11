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
use Celsius3\CoreBundle\Entity\Institution;
use Celsius3\Form\Type\InstitutionType;
use Celsius3\Form\Type\Filter\InstitutionFilterType;
use Celsius3\Exception\Exception;

/**
 * Location controller.
 *
 * @Route("/superadmin/institution")
 */
class SuperadminInstitutionController extends BaseController
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
     * @Route("/", name="superadmin_institution")
     * @Template()
     *
     * @return array
     */
    public function index()
    {
        return $this->baseIndex('Institution', $this->createForm(InstitutionFilterType::class));
    }

    /**
     * Displays a form to create a new Institution entity.
     *
     * @Route("/new", name="superadmin_institution_new")
     * @Template()
     *
     * @return array
     */
    public function new()
    {
        return $this->baseNew('Institution', new Institution(), InstitutionType::class, array(
            'instance' => $this->getDirectory(),
        ));
    }

    /**
     * Creates a new Institution entity.
     *
     * @Route("/create", name="superadmin_institution_create", methods={"POST"})
     *
     */
    public function create()
    {
        return $this->render('Superadmin/Instance/new.html.twig', $this->baseCreate('Institution', new Institution(), InstitutionType::class, array(
            'instance' => $this->getDirectory(),
        ), 'superadmin_institution'));
    }

    /**
     * Displays a form to edit an existing Institution entity.
     *
     * @Route("/{id}/edit", name="superadmin_institution_edit")
     * @Template()
     *
     * @param string $id
     *                   The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id)
    {
        return $this->baseEdit('Institution', $id, InstitutionType::class, array(
            'instance' => $this->getDirectory(),
        ));
    }

    /**
     * Edits an existing Institution entity.
     *
     * @Route("/{id}/update", name="superadmin_institution_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update($id)
    {
        return $this->render('Superadmin/Institution/edit.html.twig', $this->baseUpdate('Institution', $id, InstitutionType::class, array(
            'instance' => $this->getDirectory(),
        ), 'superadmin_institution'));
    }

    /**
     * Batch actions.
     *
     * @Route("/batch", name="superadmin_institution_batch")
     *
     * @return array
     */
    public function batch()
    {
        return $this->baseBatch();
    }

    protected function batchUnion($element_ids)
    {
        return $this->render('Superadmin/Institution/batchUnion.html.twig', $this->baseUnion('Institution', $element_ids));
    }

    /**
     * Unifies a group of Institution entities.
     *
     * @Route("/doUnion", name="superadmin_institution_doUnion")
     * @Method("post")
     *
     */
    public function doUnion()
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $element_ids = $request->request->get('element');
        $main_id = $request->request->get('main');

        return $this->baseDoUnion(Institution::class, $element_ids, $main_id, 'superadmin_institution');
    }

    /**
     * Displays a form to edit an existing Institution entity.
     *
     * @Route("/{id}/show", name="superadmin_institution_show")
     * @Template()
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function show($id)
    {
        $entity = $this->getDoctrine()->getRepository(Institution::class)->find($id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.institution');
        }

        return array(
            'entity' => $entity,
        );
    }
}
