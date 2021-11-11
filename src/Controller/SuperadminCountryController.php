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
use Celsius3\CoreBundle\Entity\Country;
use Celsius3\Form\Type\CountryType;
use Celsius3\Form\Type\Filter\CountryFilterType;

/**
 * Order controller.
 *
 * @Route("/superadmin/country")
 */
class SuperadminCountryController extends BaseController
{
    protected function getSortDefaults()
    {
        return array(
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        );
    }

    /**
     * Lists all Country entities.
     *
     * @Route("/", name="superadmin_country")
     * @Template()
     *
     * @return array
     */
    public function index()
    {
        return $this->baseIndex('Country', $this->createForm(CountryFilterType::class));
    }

    /**
     * Displays a form to create a new Country entity.
     *
     * @Route("/new", name="superadmin_country_new")
     * @Template()
     *
     * @return array
     */
    public function new()
    {
        return $this->baseNew('Country', new Country(), CountryType::class, array(
            'instance' => $this->getDirectory(),
        ));
    }

    /**
     * Creates a new Country entity.
     *
     * @Route("/create", name="superadmin_country_create", methods={"POST"})
     */
    public function create()
    {
        return $this->render('Superadmin/Country/new.html.twig', $this->baseCreate('Country', new Country(), CountryType::class, array(
            'instance' => $this->getDirectory(),
        ), 'superadmin_country'));
    }

    /**
     * Displays a form to edit an existing Country entity.
     *
     * @Route("/{id}/edit", name="superadmin_country_edit")
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
        return $this->baseEdit('Country', $id, CountryType::class, array(
            'instance' => $this->getDirectory(),
        ));
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
    public function update($id)
    {
        return $this->render('Superadmin/Country/edit.html.twig', $this->baseUpdate('Country', $id, CountryType::class, array(
            'instance' => $this->getDirectory(),
        ), 'superadmin_country'));
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
        return $this->render('Superadmin/Country/batchUnion.html.twig', $this->baseUnion('Country', $element_ids));
    }

    /**
     * Unifies a group of Country entities.
     *
     * @Route("/doUnion", name="superadmin_country_doUnion")
     * @Method("post")
     *
     * @return array
     */
    public function doUnion()
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $element_ids = $request->request->get('element');
        $main_id = $request->request->get('main');

        return $this->baseDoUnion('Country', $element_ids, $main_id, 'superadmin_country');
    }
}
