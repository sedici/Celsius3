<?php

/*
 * Celsius3 - City HTML controller
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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Entity\City;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * City HTML controller.
 * @Route("/admin/city")
 */
class AdminCityController extends HtmlEntityController
{

    final protected function getEntity(): string
    { return City::class; }


    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        ];
    }


    /**
     * Lists all City entities.
     * @Route("/", name="admin_city")
     */
    public function indexHandler(): Response
    { return parent::htmlIndex(); }


    /**
     * Displays a form to create a new City entity.
     * @Route("/new", name="admin_city_new")
     */
    public function newHandler(): Response
    { return parent::htmlNew(); }


    /**
     * Creates a new City entity.
     * @Route("/create", name="admin_city_create", methods={"POST"})
     */
    public function createHandler(): Response
    { return parent::htmlCreate(); }


    /**
     * Displays a form to edit an existing City entity.
     * @Route("/{id}/edit", name="admin_city_edit")
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function editHandler($id): Response
    { return parent::htmlEdit($id); }

    
    /**
     * Edits an existing City entity.
     * @Route("/{id}/update", name="admin_city_update", methods={"POST"})
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function updateHandler($id): Response
    { return parent::htmlUpdate($id); }
}
