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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\NewsController;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * News controller.
 * @Route("/admin/news")
 */
class HtmlAdminNewsController extends NewsController
{

    /**
     * Lists all News entities.
     * @Route("/", name="admin_news")
     */
    public function htmlIndex(): Response
    {
        return $this->htmlRenderer->render(
            templatenName: 'index',
            params: $this->index(isInstanceDependent: false)
        );
    }


    /**
     * Finds and displays a News entity.
     * @Route("/{id}/show", name="admin_news_show")
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlShow($id): Response
    {
        return $this->htmlRenderer->render(
            templatenName: 'show',
            params: $this->show(
                $id, isInstanceDependent: false
            )
        );
    }


    /**
     * Displays a form to create a new News entity.
     * @Route("/new", name="admin_news_new")
     */
    public function htmlNew(): Response
    {
        return $this->htmlRenderer->render(
            templatenName: 'new',
            params: $this->new()
        );
    }


    /**
     * Creates a new News entity.
     * @Route("/create", name="admin_news_create", methods={"POST"})
     */
    public function htmlCreate(): Response
    {
        return $this->htmlRenderer->render(
            templatenName: 'new',
            params: $this->create()
        );
    }


    /**
     * Displays a form to edit an existing News entity.
     * @Route("/{id}/edit", name="admin_news_edit")
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlEdit(string $id): Response
    {
        return $this->htmlRenderer->render(
            templatenName: 'edit',
            params: $this->edit($id)
        );
    }


    /**
     * Edits an existing News entity.
     * @Route("/{id}/update", name="admin_news_update", methods={"POST"})
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function uhtmlUpdate(string $id): Response
    {
        return $this->htmlRenderer->render(
            templatenName: 'edit',
            params: $this->update($id)
        );
    }
}