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

use Celsius3\Controller\Base\CityController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * City HTML controller.
 * @Route("/admin/city")
 */
class HtmlAdminCityController extends CityController
{

    /**
     * @Route("/", name="admin_city")
     */
    public function htmlIndex(): Response
    { return $this->htmlRenderer->render(templateName: 'index', params: $this->index()); }


    /**
     * @Route("/new", name="admin_city_new")
     */
    public function htmlNew(): Response
    { return $this->htmlRenderer->render(templateName: 'new', params: $this->new()); }


    /**
     * @Route("/create", name="admin_city_create", methods={"POST"})
     */
    public function htmlCreate(): RedirectResponse|Response
    { return $this->htmlRenderer->render(templateName: 'create', params: $this->create()); }


    /**
     * @Route("/{id}/edit", name="admin_city_edit")
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlEdit($id): Response
    { return $this->htmlRenderer->render(templateName: 'edit', params: $this->edit($id)); }

    
    /**
     * @Route("/{id}/update", name="admin_city_update", methods={"POST"})
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlUpdate($id): RedirectResponse|Response
    { return $this->htmlRenderer->render(templateName: 'update', params: $this->update($id)); }
}