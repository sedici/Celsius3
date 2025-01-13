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

namespace Celsius3\Controller\Web;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\CityController;
use Celsius3\Controller\Rendering\HtmlRenderingController;
use Celsius3\Controller\Rendering\RenderingController;

/**
 * Location controller.
 *
 * @Route("/admin/city")
 */
class AdminCityController extends CityController
{

    protected function getRenderingController(): RenderingController
    {
        $renderingController = new HtmlRenderingController();
        $renderingController->setControllerInstance($this);
        return $renderingController;
    }


    /**
     * Lists all City entities.
     *
     * @Route("/", name="admin_city")
     */
    public function index(): Response
    // { return $this->baseInstanceIndex(); }
    // {
    //     return $this->render('Admin/City/index.html.twig', $this->baseInstanceIndex1());
    // }
    {
        return $this->renderingController->renderResponse(
            parameters: $this->baseInstanceIndex1(),
            twigPostfix: 'index'
        );
    }


    /**
     * Displays a form to create a new City entity.
     *
     * @Route("/new", name="admin_city_new")
     */
    public function new(): Response
    { return $this->baseInstanceNew(); }


    /**
     * Creates a new City entity.
     *
     * @Route("/create", name="admin_city_create", methods={"POST"})
     */
    public function create(): RedirectResponse|Response
    { return $this->baseInstanceCreate(route: 'admin_city_new'); }


    /**
     * Displays a form to edit an existing City entity.
     *
     * @Route("/{id}/edit", name="admin_city_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id): Response
    { return $this->baseInstanceEdit($id); }

    
    /**
     * Edits an existing City entity.
     *
     * @Route("/{id}/update", name="admin_city_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update($id): RedirectResponse|Response
    { return $this->baseInstanceUpdate($id, 'admin_city_edit'); }
}
