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

use Celsius3\Entity\Instance;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Celsius3\Controller\Base\InstitutionController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[
    Route('/admin/institution'),
    IsGranted(data: 'ROLE_ADMIN')
]
class HtmlAdminInstitutionController extends InstitutionController
{

    protected function getDirectory(): Instance|null
    {
        return $this->entityManager
            ->getRepository(Instance::class)
            ->findOneBy(['url' => 'directory']);
    }


    #[Route(
        '/',
        name: 'admin_institution'
    )]
    public function htmlIndex(): Response
    {
        return $this->htmlRenderer->render(
            'index',
            $this->index()
        );
    }


    #[Route(
        '/new',
        name: 'admin_institution_new',
        options: ['expose' => true]
    )]
    public function htmlNew(): Response
    {
        return $this->htmlRenderer->render(
            'new',
            $this->new(formOptions: [ 'show_city' => true ])
        );
    }


    #[Route(
        '/create',
        name: 'admin_institution_create',
        methods: ['POST']
    )]
    public function htmlCreate(): Response
    {
        return $this->htmlRenderer->render(
            'new',
            $this->create(formOptions: [ 'show_city' => true ])
        );
    }


    #[Route(
        '/{id}/edit',
        name: 'admin_institution_edit'
    )]
    public function htmlEdit(string $id): Response
    {
        return $this->htmlRenderer->render(
            'edit',
            $this->edit($id, formOptions: [ 'show_city' => true ])
        );
    }


    #[Route(
        '/{id}/update',
        name: 'admin_institution_update',
        methods: ['POST']
    )]
    public function htmlUpdate(string $id): RedirectResponse|Response
    {
        return $this->htmlRenderer->render(
            'edit',
            $this->update($id, formOptions: [ 'show_city' => true ])
        );
    }


    #[Route(
        '/{id}/show',
        name: 'admin_institution_show'
    )]
    public function htmlShow(string $id): Response
    {
        $entity = $this->findQuery($id);

        // Primero verifica que el usuario tenga acceso (ademas de que exista la entidad)
        // pero si no existe la entidad o no tiene acceso manda que no tiene acceso para brindar menos información
        if (
            $entity !== null
            && $entity->instance !== $this->directory
            && $entity->instance !== $this->instance
        ) $this->error('access_denied', msg: '');

        return $this->htmlRenderer->render(
            'show',
            $this->show($id, isInstanceDependent: false)
        );
    }
}
