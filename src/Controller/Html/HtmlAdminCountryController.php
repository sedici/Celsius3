<?php

/*
 * Celsius3 - Country HTML controller
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

use Celsius3\Controller\Base\CountryController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Country HTML controller.
 * @Route("/admin/country")
 */
class HtmlAdminCountryController extends CountryController
{

    /**
     * @Route("/", name="admin_country")
     */
    public function htmlIndex(): Response
    {
        return $this->htmlRenderer->render(
            'index',
            $this->index()
        );
    }


    /**
     * @Route("/new", name="admin_country_new")
     */
    public function htmlNew(): Response
    {
        return $this->htmlRenderer->render(
            'new',
            $this->new()
        );
    }


    /**
     * @Route("/create", name="admin_country_create", methods={"POST"})
     */
    public function htmlCreate(): RedirectResponse|Response
    {
        return $this->htmlRenderer->render(
            'create',
            $this->create()
        );
    }


    /**
     * @Route("/{id}/edit", name="admin_country_edit")
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlEdit($id): Response
    {
        return $this->htmlRenderer->render(
            'edit',
            $this->edit($id)
        );
    }


    /**
     * @Route("/{id}/update", name="admin_country_update", methods={"POST"})
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlUpdate($id): RedirectResponse|Response
    {
        return $this->htmlRenderer->render(
            'update',
            $this->update($id)
        );
    }
}