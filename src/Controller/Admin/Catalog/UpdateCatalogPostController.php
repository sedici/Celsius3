<?php

declare(strict_types=1);

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

namespace Celsius3\Controller\Admin\Catalog;

use Celsius3\CoreBundle\Entity\Catalog;
use Celsius3\CoreBundle\Exception\Exception;
use Celsius3\Form\Type\CatalogType;
use Celsius3\CoreBundle\Helper\InstanceHelper;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

final class UpdateCatalogPostController extends AbstractController
{
    private $translator;
    private $catalogRepository;
    private $instanceHelper;
    private $entityManager;

    public function __construct(
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        InstanceHelper $instanceHelper
    ) {
        $this->translator = $translator;
        $this->catalogRepository = $entityManager->getRepository(Catalog::class);
        $this->instanceHelper = $instanceHelper;
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request, $id): Response
    {
        $instance = $this->instanceHelper->getSessionInstance();

        $entity = $this->catalogRepository
            ->findOneForInstance($instance, $id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.catalog');
        }

        $edit_form = $this->createForm(CatalogType::class, $entity, ['instance' => $instance,]);

        $edit_form->handleRequest($request);
        if ($edit_form->isValid()) {
            try {
                $this->entityManager->persist($entity);
                $this->entityManager->flush();

                $this->addFlash(
                    'success',
                    $this->translator->trans(
                        'The %entity% was successfully edited.',
                        ['%entity%' => $this->translator->trans('catalog')],
                        'Flashes'
                    )
                );

                return $this->redirect($this->generateUrl('admin_catalog_edit', ['id' => $id]));
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash(
                    'error',
                    $this->translator->trans(
                        'The %entity% already exists.',
                        ['%entity%' => $this->translator->trans('catalog')],
                        'Flashes'
                    )
                );
            }
        }

        $this->addFlash(
            'error',
            $this->translator->trans(
                'There were errors editing the %entity%.',
                ['%entity%' => $this->translator->trans('catalog')],
                'Flashes'
            )
        );

        return $this->render(
            'Admin/Catalog/edit.html.twig',
            [
                'entity' => $entity,
                'edit_form' => $edit_form->createView(),
            ]
        );
    }
}
