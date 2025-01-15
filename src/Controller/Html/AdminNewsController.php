<?php

/*
 * Celsius3 - News HTML controller
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
use Celsius3\Entity\News;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * News HTML controller.
 * @Route("/admin/news")
 */
class AdminNewsController extends HtmlEntityController
{
    final protected function getEntity(): string
    { return News::class; }

    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.updatedAt',
            'defaultSortDirection' => 'desc'
        ];
    }

    /**
     * Lists all News entities.
     * @Route("/", name="admin_news")
     */
    public function indexHandler(): Response
    { return parent::htmlIndex(isInstanceDependent: false); }


    /**
     * Finds and displays a News entity.
     * @Route("/{id}/show", name="admin_news_show")
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function showHandler(string $id): Response
    { return parent::htmlShow($id, isInstanceDependent: false); }


    /**
     * Displays a form to create a new News entity.
     * @Route("/new", name="admin_news_new")
     */
    public function newHandler(): Response
    { return parent::htmlNew(); }

    /**
     * Creates a new News entity.
     * @Route("/create", name="admin_news_create", methods={"POST"})
     */
    public function createHandler(): Response
    { return parent::htmlCreate(); }

    /**
     * Displays a form to edit an existing News entity.
     * @Route("/{id}/edit", name="admin_news_edit")
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function editHandler($id): Response
    { return parent::htmlEdit($id); }

    /**
     * Edits an existing News entity.
     * @Route("/{id}/update", name="admin_news_update", methods={"POST"})
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function updateHandler($id): Response
    { return parent::htmlUpdate($id); }
}
