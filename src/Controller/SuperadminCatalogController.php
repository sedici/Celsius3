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
use Celsius3\Entity\Instance;
use Celsius3\Form\Type\Filter\CatalogFilterType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Location controller.
 *
 * @Route("/superadmin/catalog")
 */
class SuperadminCatalogController extends CatalogController
{

    protected function getInstance(): Instance
    { return $this->directory; }


    /**
     * Lists all Catalog entities.
     *
     * @Route("/", name="superadmin_catalog")
     */
    public function index(): Response
    { return $this->baseInstanceIndex(CatalogFilterType::class); }


    /**
     * Displays a form to create a new Catalog entity.
     *
     * @Route("/new", name="superadmin_catalog_new")
     */
    public function new(): Response
    { return $this->baseInstanceNew(); }


    /**
     * Creates a new Catalog entity.
     *
     * @Route("/create", name="superadmin_catalog_create", methods={"POST"})
     *
     */
    public function create(): Response
    { return $this->baseInstanceCreate(route: 'superadmin_catalog'); }


    /**
     * Displays a form to edit an existing Catalog entity.
     *
     * @Route("/{id}/edit", name="superadmin_catalog_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit(string $id): Response
    { return $this->baseInstanceEdit($id); }


    /**
     * Edits an existing Catalog entity.
     *
     * @Route("/{id}/update", name="superadmin_catalog_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update(string $id): Response
    { return $this->baseInstanceUpdate($id, 'superadmin_catalog'); }


    /**
     * Batch actions.
     *
     * @Route("/batch", name="superadmin_catalog_batch")
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function batch()
    { return $this->baseBatch(); }


    protected function batchUnion($element_ids): Response
    {
        return $this->render(
            (string) $this->templatePrefix . 'batchUnion.html.twig',
            $this->baseUnion($element_ids)
        );
    }


    /**
     * Unifies a group of Catalog entities.
     *
     * @Route("/doUnion", name="superadmin_catalog_doUnion", methods={"POST"})
     */
    public function doUnion(Request $request): RedirectResponse
    {
        $elementIds = $request->request->get('element');
        $mainId = $request->request->get('main');

        return $this->baseDoUnion(
            $elementIds, $mainId, 'superadmin_catalog'
        );
    }
}
