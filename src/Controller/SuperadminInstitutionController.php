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

use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\Entity\Institution;
use Celsius3\Form\Type\InstitutionType;
use Celsius3\Form\Type\Filter\InstitutionFilterType;
use Celsius3\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Translation\Translator;
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
     */
    public function index(PaginatorInterface $paginator): Response
    {
        return $this->render(
            'Superadmin/Institution/index.html.twig',
            $this->baseIndex('Institution', $this->createForm(InstitutionFilterType::class),$paginator)
        );
    }
    protected function listQuery($name)
    {
        $valor=$name;
    //    $class = new \ReflectionClass($valor);
        return $this->getDoctrine()->getManager()
            ->getRepository(Institution::class)
            ->createQueryBuilder('e');
    }


    protected function baseIndex($name, FormInterface $filter_form = null,$paginator)
    {

        $query = $this->listQuery($name);
        $request = $this->get('request_stack')->getCurrentRequest();
        if (!is_null($filter_form)) {
            $filter_form = $filter_form->handleRequest($request);
            //  $query = $this->filter($name, $filter_form, $query);
        }
        //    $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate($query, $request->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */, $this->getSortDefaults());

        return array(
            'pagination' => $pagination,
            'filter_form' => (!is_null($filter_form)) ? $filter_form->createView() : $filter_form,
        );
    }
    /**
     * Displays a form to create a new Institution entity.
     *
     * @Route("/new", name="superadmin_institution_new")
     */
    public function new(): Response
    {
        return $this->render(
            'Superadmin/Institution/new.html.twig',
            $this->baseNew('Institution', new Institution(), InstitutionType::class, [
                'instance' => $this->getDirectory(),
            ])
        );
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
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id): Response
    {
        return $this->render(
            'Superadmin/Institution/edit.html.twig',
            $this->baseEdit('Institution', $id, InstitutionType::class, [
                'instance' => $this->getDirectory(),
            ])
        );
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
     * @Route("/doUnion", name="superadmin_institution_doUnion", methods={"POST"})
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
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function show($id): Response
    {
        $entity = $this->getDoctrine()->getRepository(Institution::class)->find($id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.institution');
        }

        return $this->render(
            'Superadmin/Institution/show.html.twig',
            [
                'entity' => $entity,
            ]
        );
    }
}
