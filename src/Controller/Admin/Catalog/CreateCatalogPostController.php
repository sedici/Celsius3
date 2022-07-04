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

use Celsius3\Entity\Catalog;
use Celsius3\Form\Type\CatalogType;
use Celsius3\Helper\InstanceHelper;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

final class CreateCatalogPostController extends AbstractController
{
    private $translator;
    private $instanceHelper;
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator,
        InstanceHelper $instanceHelper
    ) {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->instanceHelper = $instanceHelper;
    }

    public function __invoke(Request $request): Response
    {
        $instance = $this->instanceHelper->getSessionInstance();

        $catalog = new Catalog();
        $name = 'Catalog';

        $form = $this->createForm(
            CatalogType::class,
            $catalog,
            [
                'instance' => $instance,
            ]
        );

        $form->handleRequest($request);
        if ($form->isValid()) {
            try {
                $this->entityManager->persist($catalog);
                $this->entityManager->flush();

                $this->addFlash(
                    'success',
                    $this->translator->trans(
                        'The %entity% was successfully created.',
                        ['%entity%' => $this->translator->trans($name)],
                        'Flashes'
                    )
                );

                return $this->redirect($this->generateUrl('admin_catalog'));
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash(
                    'error',
                    $this->translator->trans(
                        'The %entity% already exists.',
                        ['%entity%' => $this->translator->trans($name)],
                        'Flashes'
                    )
                );
            }
        }

        $this->addFlash(
            'error',
            $this->translator->trans(
                'There were errors creating the %entity%.',
                ['%entity%' => $this->translator->trans($name)],
                'Flashes'
            )
        );

        return $this->render(
            'Admin/Catalog/new.html.twig',
            [
                'entity' => $catalog,
                'form' => $form->createView(),
            ]
        );
    }
}
