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
use Celsius3\CoreBundle\Entity\News;
use Celsius3\Form\Type\NewsType;
use Celsius3\Form\Type\Filter\NewsFilterType;

/**
 * News controller.
 *
 * @Route("/admin/news")
 */
class AdminNewsController extends BaseInstanceDependentController
{
    /**
     * Lists all News entities.
     *
     * @Route("/", name="admin_news")
     */
    public function index()
    {
        return $this->render(
            'Admin/News/index.html.twig',
            $this->baseIndex('News', $this->createForm(NewsFilterType::class))
        );
    }

    /**
     * Finds and displays a News entity.
     *
     * @Route("/{id}/show", name="admin_news_show")
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function show($id)
    {
        return $this->render('Admin/News/show.html.twig', $this->baseShow('News', $id));
    }

    /**
     * Displays a form to create a new News entity.
     *
     * @Route("/new", name="admin_news_new")
     */
    public function new()
    {
        return $this->render(
            'Admin/News/new.html.twig',
            $this->baseNew('News', new News(), NewsType::class, [
                'instance' => $this->getInstance(),
            ])
        );
    }

    /**
     * Creates a new News entity.
     *
     * @Route("/create", name="admin_news_create", methods={"POST"})
     */
    public function create()
    {
        return $this->render('Admin/News/new.html.twig', $this->baseCreate('News', new News(), NewsType::class, array(
            'instance' => $this->getInstance(),
        ), 'admin_news'));
    }

    /**
     * Displays a form to edit an existing News entity.
     *
     * @Route("/{id}/edit", name="admin_news_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id)
    {
        return $this->render(
            'Admin/Mail/edit.html.twig',
            $this->baseEdit('News', $id, NewsType::class, array(
                'instance' => $this->getInstance(),
            ))
        );
    }

    /**
     * Edits an existing News entity.
     *
     * @Route("/{id}/update", name="admin_news_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update($id)
    {
        return $this->render('Admin/News/edit.html.twig', $this->baseUpdate('News', $id, NewsType::class, array(
            'instance' => $this->getInstance(),
        ), 'admin_news'));
    }
}
