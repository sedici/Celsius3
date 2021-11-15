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
use Celsius3\Entity\City;
use Celsius3\Form\Type\CityType;
use Celsius3\Form\Type\Filter\CityFilterType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Location controller.
 *
 * @Route("/superadmin/city")
 */
class SuperadminCityController extends BaseController
{
    protected function getSortDefaults()
    {
        return array(
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        );
    }

    /**
     * Lists all City entities.
     *
     * @Route("/", name="superadmin_city")
     */
    public function index(): Response
    {
        return $this->render(
            'Superadmin/City/index.html.twig',
            $this->baseIndex('City', $this->createForm(CityFilterType::class))
        );
    }

    /**
     * Displays a form to create a new City entity.
     *
     * @Route("/new", name="superadmin_city_new")
     */
    public function new(): Response
    {
        return $this->render(
            'Superadmin/City/new.html.twig',
            $this->baseNew('City', new City(), CityType::class, [
                'instance' => $this->getDirectory(),
            ])
        );
    }

    /**
     * Creates a new City entity.
     *
     * @Route("/create", name="superadmin_city_create", methods={"POST"})
     */
    public function create()
    {
        return $this->render("Superadmin/City/new.html.twig", $this->baseCreate('City', new City(), CityType::class, array(
            'instance' => $this->getDirectory(),
        ), 'superadmin_city'));
    }

    /**
     * Displays a form to edit an existing City entity.
     *
     * @Route("/{id}/edit", name="superadmin_city_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id): Response
    {
        return $this->render(
            'Superadmin/City/new.html.twig',
            $this->baseEdit('City', $id, CityType::class, [
                'instance' => $this->getDirectory(),
            ])
        );
    }

    /**
     * Edits an existing City entity.
     *
     * @Route("/{id}/update", name="superadmin_city_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update($id)
    {
        return $this->render('Superadmin/City/edit.html.twig', $this->baseUpdate('City', $id, CityType::class, array(
            'instance' => $this->getDirectory(),
        ), 'superadmin_city'));
    }

    /**
     * Batch actions.
     *
     * @Route("/batch", name="superadmin_city_batch")
     */
    public function batch()
    {
        return $this->baseBatch();
    }

    protected function batchUnion($element_ids)
    {
        return $this->render('Superadmin/City/batchUnion.html.twig', $this->baseUnion('City', $element_ids));
    }

    /**
     * Unifies a group of City entities.
     *
     * @Route("/doUnion", name="superadmin_city_doUnion", methods={"POST"})
     */
    public function doUnion()
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $element_ids = $request->request->get('element');
        $main_id = $request->request->get('main');

        return $this->baseDoUnion(City::class, $element_ids, $main_id, 'superadmin_city');
    }
}
