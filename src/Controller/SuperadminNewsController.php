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

use Celsius3\Entity\Instance;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Celsius3\Form\Type\Filter\NewsFilterType;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

/**
 * News controller.
 *
 * @Route("/superadmin/news")
 */
class SuperadminNewsController extends NewsController
{

    protected function getInstance(): Instance
    { return $this->directory; }


    protected function listQuery(): QueryBuilder
    {
        return $this->repository
            ->createQueryBuilder('e')
            ->where('e.instance = :instance')
            ->setParameter(
                'instance',
                $this->directory->getId()
            );
    }


    /**
     * Lists all News entities.
     *
     * @Route("/", name="superadmin_news")
     */
    public function index(): Response
    { return $this->baseInstanceIndex(NewsFilterType::class); }


    /**
     * Finds and displays a News entity.
     *
     * @Route("/{id}/show", name="superadmin_news_show")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function show(string $id): Response
    { return $this->baseShow($id); }


    /**
     * Displays a form to create a new News entity.
     *
     * @Route("/new", name="superadmin_news_new")
     */
    public function new(): Response
    { return $this->baseInstanceNew(); }


    /**
     * Creates a new News entity.
     *
     * @Route("/create", name="superadmin_news_create", methods={"POST"})
     */
    public function create(): Response
    { return $this->baseInstanceCreate(route: 'superadmin_news'); }


    /**
     * Displays a form to edit an existing News entity.
     *
     * @Route("/{id}/edit", name="superadmin_news_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit(string $id): Response
    { return $this->baseInstanceEdit($id); }


    /**
     * Edits an existing News entity.
     *
     * @Route("/{id}/update", name="superadmin_news_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update(string $id): Response
    { return $this->baseInstanceUpdate($id, 'superadmin_news'); }
}
