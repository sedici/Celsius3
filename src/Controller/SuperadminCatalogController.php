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
use Celsius3\CoreBundle\Entity\Catalog;
use Celsius3\Form\Type\CatalogType;
use Celsius3\Form\Type\Filter\CatalogFilterType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Location controller.
 *
 * @Route("/superadmin/catalog")
 */
class SuperadminCatalogController extends BaseController
{
    /**
     * Lists all Catalog entities.
     *
     * @Route("/", name="superadmin_catalog")
     * @Template()
     *
     * @return array
     */
    public function index()
    {
        return $this->baseIndex('Catalog', $this->createForm(CatalogFilterType::class));
    }

    /**
     * Displays a form to create a new Catalog entity.
     *
     * @Route("/new", name="superadmin_catalog_new")
     * @Template()
     *
     * @return array
     */
    public function new()
    {
        return $this->baseNew('Catalog', new Catalog(), CatalogType::class, array(
            'instance' => $this->getDirectory(),
        ));
    }

    /**
     * Creates a new Catalog entity.
     *
     * @Route("/create", name="superadmin_catalog_create", methods={"POST"})
     *
     */
    public function create()
    {
        return $this->render('Superadmin/Catalog/new.html.twig', $this->baseCreate('Catalog', new Catalog(), CatalogType::class, array(
            'instance' => $this->getDirectory(),
        ), 'superadmin_catalog'));
    }

    /**
     * Displays a form to edit an existing Catalog entity.
     *
     * @Route("/{id}/edit", name="superadmin_catalog_edit")
     *
     * @param string $id
     *                   The entity ID
     *
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id)
    {
        return $this->render('Superadmin/Catalog/edit.html.twig', $this->baseEdit('Catalog', $id, CatalogType::class, array(
            'instance' => $this->getDirectory(),
        )));
    }

    /**
     * Edits an existing Catalog entity.
     *
     * @Route("/{id}/update", name="superadmin_catalog_update", methods={"POST"})
     *
     * @param string $id
     *                   The entity ID
     *
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update($id)
    {
        return $this->render('Superadmin/Catalog/edit.html.twig', $this->baseUpdate('Catalog', $id, CatalogType::class, array(
            'instance' => $this->getDirectory(),
        ), 'superadmin_catalog'));
    }

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
    {
        return $this->baseBatch();
    }

    protected function batchUnion($element_ids)
    {
        return $this->render('Superadmin/Catalog/batchUnion.html.twig', $this->baseUnion('Catalog', $element_ids));
    }

    /**
     * Unifies a group of Catalog entities.
     *
     * @Route("/doUnion", name="superadmin_catalog_doUnion", methods={"POST"})
     */
    public function doUnion(Request $request)
    {
        $elementIds = $request->request->get('element');
        $mainId = $request->request->get('main');

        return $this->baseDoUnion('Catalog', $elementIds, $mainId, 'superadmin_catalog');
    }
}
