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

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\NewsController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


/**
 * News controller.
 */
#[
    Route('/superadmin/news'),
    IsGranted('ROLE_SUPER_ADMIN')
]
class HtmlSuperadminNewsController extends NewsController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->setInstance($this->directory);
        $this->setInstanceDependent(false);
    }


    public function listQuery(?bool $isInstanceDependent = null): QueryBuilder
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
     */
    #[Route('/', name: 'superadmin_news')]
    public function htmlIndex(): Response
    {
        return $this->htmlRenderer->render(
            'index',
            $this->index()
        );
        // return $this->baseInstanceIndex(NewsFilterType::class);
    }


    /**
     * Finds and displays a News entity.
     * @throws NotFoundHttpException If entity doesn't exists
     */
    #[Route('/{id}', name: 'superadmin_news_show')]
    public function htmlShow(string $id): Response
    {
        return $this->htmlRenderer->render(
            'show',
            $this->show($id)
        );
    }


    /**
     * Displays a form to create a new News entity.
     */
    #[Route('/new', name: 'superadmin_news_new')]
    public function htmlNew(): Response
    {
        return $this->htmlRenderer->render(
            'new',
            $this->new()
        );
    }


    /**
     * Creates a new News entity.
     */
    #[Route('/create', name: 'superadmin_news_create', methods: ['POST'])]
    public function htmlCreate(): Response
    {
        return $this->htmlRenderer->render(
            'new',
            $this->create()
        );
    }


    /**
     * Displays a form to edit an existing News entity.
     * @throws NotFoundHttpException If entity doesn't exists
     */
    #[Route('/{id}/edit', name: 'superadmin_news_edit')]
    public function htmlEdit(string $id): Response
    {
        return $this->htmlRenderer->render(
            'edit',
            $this->edit($id, isInstanceDependent: true)
        );
    }


    /**
     * Edits an existing News entity.
     * @throws NotFoundHttpException If entity doesn't exists
     */
    #[Route('/{id}/update', name: 'superadmin_news_update', methods: ['POST'])]
    public function htmlUpdate(string $id): Response
    {
        return $this->htmlRenderer->render(
            'edit',
            $this->update($id, isInstanceDependent: true)
        );
    }
}
