<?php
/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

namespace Celsius3\CoreBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Entity\News;
use Celsius3\CoreBundle\Form\Type\NewsType;
use Celsius3\CoreBundle\Filter\Type\NewsFilterType;

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
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this
                ->baseIndex('News', $this->createForm(new NewsFilterType()));
    }

    /**
     * Finds and displays a News entity.
     *
     * @Route("/{id}/show", name="admin_news_show")
     * @Template()
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function showAction($id)
    {
        return $this->baseShow('News', $id);
    }

    /**
     * Displays a form to create a new News entity.
     *
     * @Route("/new", name="admin_news_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this
                ->baseNew('News', new News(),
                        new NewsType($this->getInstance()));
    }

    /**
     * Creates a new News entity.
     *
     * @Route("/create", name="admin_news_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminNews:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this
                ->baseCreate('News', new News(),
                        new NewsType($this->getInstance()), 'admin_news');
    }

    /**
     * Displays a form to edit an existing News entity.
     *
     * @Route("/{id}/edit", name="admin_news_edit")
     * @Template()
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('News', $id, new NewsType($this->getInstance()));
    }

    /**
     * Edits an existing News entity.
     *
     * @Route("/{id}/update", name="admin_news_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminNews:edit.html.twig")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function updateAction($id)
    {
        return $this
                ->baseUpdate('News', $id, new NewsType($this->getInstance()),
                        'admin_news');
    }

    /**
     * Deletes a News entity.
     *
     * @Route("/{id}/delete", name="admin_news_delete")
     * @Method("post")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function deleteAction($id)
    {
        return $this->baseDelete('News', $id, 'admin_news');
    }
}