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
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Celsius3\Entity\Institution;
use Celsius3\Form\Type\InstitutionType;
use Celsius3\Form\Type\Filter\InstitutionFilterType;
use Celsius3\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Location controller.
 *
 * @Route("/admin/institution")
 */
class AdminInstitutionController extends BaseInstanceDependentController
{

    protected final function getEntity(): string
    { return Institution::class; }

    protected final function getType(): string
    { return InstitutionType::class; }

    protected final function getTemplatePrefix(): string
    { return 'Admin/Institution/'; }

    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        ];
    }


    protected function getDirectory()
    {
        return $this->managerRegistry
            ->getRepository(Instance::class)
            ->findOneBy(['url' => 'directory']);
    }


    /**
     * Lists all Institution entities.
     *
     * @Route("/", name="admin_institution")
     */
    public function index(PaginatorInterface $paginator)
    {
        return $this->render(
            (string) $this->templatePrefix . 'index.html.twig',
            $this->baseIndex(
                $this->createForm(
                    InstitutionFilterType::class,
                    null,
                    [
                        'instance' => $this->instance,
                    ]
                ),
                $paginator
            )
        );
    }

    /**
     * Displays a form to create a new Institution entity.
     *
     * @Route("/new", name="admin_institution_new", options={"expose"=true})
     */
    public function new(): Response
    {
        $entityClassName = $this->entityClassName;

        return $this->render(
            (string) $this->templatePrefix . 'new.html.twig',
            $this->baseNew(
                $entityClassName,
                new $entityClassName(),
                $this->typeClassName,
                [
                    'instance' => $this->instance,
                    'show_city' => true
                ]
            )
        );
    }

    /**
     * Creates a new Institution entity.
     *
     * @Route("/create", name="admin_institution_create", methods={"POST"})
     */
    public function create()
    {
        $entityClassName = $this->entityClassName;

        return $this->render(
            (string) $this->templatePrefix . 'new.html.twig',
            $this->baseCreate(
                $entityClassName,
                new $entityClassName(),
                $this->typeClassName,
                [
                    'instance' => $this->instance,
                    'show_city' => true
                ],
                'admin_institution'
            )
        );
    }

    /**
     * Displays a form to edit an existing Institution entity.
     *
     * @Route("/{id}/edit", name="admin_institution_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id): Response
    {
        return $this->render(
            (string) $this->templatePrefix . 'edit.html.twig',
            $this->baseEdit(
                $this->entityClassName,
                $id,
                $this->typeClassName,
                [
                    'instance' => $this->instance,
                    'show_city' => true
                ]
            )
        );
    }

    /**
     * Edits an existing Institution entity.
     *
     * @Route("/{id}/update", name="admin_institution_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update($id)
    {
        $response = $this->baseUpdate(
            (string) $this->entityClassName,
            $id,
            $this->typeClassName,
            [
                'instance' => $this->instance,
                'show_city' => true
            ],
            'admin_institution'
        );

        if ($response instanceof RedirectResponse) {
            return $response;
        }

        return $this->render(
            $this->templatePrefix . 'edit.html.twig',
            $response
        );
    }

    /**
     * Displays a form to edit an existing Institution entity.
     *
     * @Route("/{id}/show", name="admin_institution_show")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function show(string $id): Response
    {
        $entity = $this->findQuery($id);

        if (!$entity) {
            throw Exception::create(
                Exception::ENTITY_NOT_FOUND,
                'exception.entity_not_found.institution'
            );
        }

        if (
            $entity->instance !== $this->getDirectory()
            && $entity->instance !== $this->instance
        ) {
            throw Exception::create(Exception::ACCESS_DENIED);
        }

        return $this->render(
            $this->templatePrefix . '/show.html.twig',
            [
                'entity' => $entity,
            ]
        );
    }
}
