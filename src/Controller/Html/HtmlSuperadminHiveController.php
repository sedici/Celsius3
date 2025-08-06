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

use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\HiveController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Hive controller.
 */
#[
    Route('/superadmin/hive'),
    IsGranted('ROLE_SUPER_ADMIN')
]
class HtmlSuperadminHiveController extends HiveController
{


    /**
     * Lists all Hive entities.
     */
    #[Route('/', name: 'superadmin_hive')]
    public function htmlIndex(): Response
    { return $this->htmlRenderer->render('index', $this->index(hasFilterForm: false));}


    /**
     * Displays a form to create a new Hive entity.
     */
    #[Route('/new', name: 'superadmin_hive_new')]
    public function htmlNew(): Response
    { return $this->htmlRenderer->render('new', $this->new()); }


    /**
     * Creates a new Hive entity.
     */
    #[Route('/create', name: 'superadmin_hive_create', methods: ['POST'])]
    public function htmlCreate()
    { return $this->htmlRenderer->render('create', $this->create()); }


    /**
     * Displays a form to edit an existing Hive entity.
     * @throws NotFoundHttpException If entity doesn't exists
     */
    #[Route('/{id}/edit', name: 'superadmin_hive_edit')]
    public function htmlEdit(string $id): Response
    { return $this->htmlRenderer->render('edit', $this->edit($id)); }


    /**
     * Edits an existing Hive entity.
     * @throws NotFoundHttpException If entity doesn't exists
     */
    #[Route('/{id}/update', name: 'superadmin_hive_update', methods: ['POST'])]
    public function htmlUpdate(string $id): Response
    { return $this->htmlRenderer->render('edit', $this->update($id)); }
}
