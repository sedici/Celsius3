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

namespace Celsius3\Controller\Html;

use Celsius3\Entity\Instance;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Celsius3\Controller\Base\CountryController;

/**
 * Order controller.
 *
 * @Route("/superadmin/country")
 */
class SuperadminCountryController extends CountryController
{

    protected function getInstance(): Instance
    { return $this->directory; }


    /**
     * Lists all Country entities.
     *
     * @Route("/", name="superadmin_country")
     */
    public function index(): Response
    {
        return $this->baseIndex();
    }


    /**
     * Displays a form to create a new Country entity.
     *
     * @Route("/new", name="superadmin_country_new")
     */
    public function new(): Response
    {
        return $this->baseNew();
    }


    /**
     * Creates a new Country entity.
     *
     * @Route("/create", name="superadmin_country_create", methods={"POST"})
     */
    public function create()
    {
        return $this->baseCreate(route: 'superadmin_country');
    }


    /**
     * Displays a form to edit an existing Country entity.
     *
     * @Route("/{id}/edit", name="superadmin_country_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit(string $id): Response
    {
        return $this->baseInstanceEdit($id);
    }


    /**
     * Edits an existing Country entity.
     *
     * @Route("/{id}/update", name="superadmin_country_update", methods={"POST"})
     *
     * @param string $id
     *                   The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update(string $id): RedirectResponse|Response
    {
        return $this->baseInstanceUpdate($id, 'superadmin_country');
    }


    /**
     * Batch actions.
     *
     * @Route("/batch", name="superadmin_country_batch")
     *
     * @return array
     */
    public function batch()
    {
        return $this->baseBatch();
    }

    protected function batchUnion($element_ids)
    {
        return $this->render(
            (string) $this->templatePrefix . 'batchUnion.html.twig',
            $this->baseUnion($element_ids)
        );
    }

    /**
     * Unifies a group of Country entities.
     *
     * @Route("/doUnion", name="superadmin_country_doUnion", methods={"POST"})
     */
    public function doUnion(): RedirectResponse
    {
        $request = $this->requestStack->getCurrentRequest();
        $element_ids = $request->request->get('element');
        $main_id = $request->request->get('main');

        return $this->baseDoUnion(
            $element_ids,
            $main_id,
            'superadmin_country'
        );
    }
}
