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
use Celsius3\CoreBundle\Entity\Catalog;
use Celsius3\CoreBundle\Form\Type\CatalogType;
use Celsius3\CoreBundle\Filter\Type\CatalogFilterType;

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
    public function indexAction()
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
    public function newAction()
    {
        return $this->baseNew('Catalog', new Catalog(), CatalogType::class);
    }

    /**
     * Creates a new Catalog entity.
     *
     * @Route("/create", name="superadmin_catalog_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminCatalog:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Catalog', new Catalog(), CatalogType::class, array(
            'instance' => $this->getDirectory(),
        ), 'superadmin_catalog');
    }

    /**
     * Displays a form to edit an existing Catalog entity.
     *
     * @Route("/{id}/edit", name="superadmin_catalog_edit")
     * @Template()
     *
     * @param string $id
     *                   The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('Catalog', $id, CatalogType::class, array(
            'instance' => $this->getDirectory(),
        ));
    }

    /**
     * Edits an existing Catalog entity.
     *
     * @Route("/{id}/update", name="superadmin_catalog_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminCatalog:edit.html.twig")
     *
     * @param string $id
     *                   The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('Catalog', $id, CatalogType::class, array(
            'instance' => $this->getDirectory(),
        ), 'superadmin_catalog');
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
    public function batchAction()
    {
        return $this->baseBatch();
    }

    protected function batchUnion($element_ids)
    {
        return $this->render('Celsius3CoreBundle:SuperadminCatalog:batchUnion.html.twig', $this->baseUnion('Catalog', $element_ids));
    }

    /**
     * Unifies a group of Catalog entities.
     *
     * @Route("/doUnion", name="superadmin_catalog_doUnion")
     * @Method("post")
     *
     * @return array
     */
    public function doUnionAction()
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $element_ids = $request->request->get('element');
        $main_id = $request->request->get('main');

        return $this->baseDoUnion('Catalog', $element_ids, $main_id, 'superadmin_catalog');
    }
}
