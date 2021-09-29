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
use Celsius3\Form\Type\Filter\CatalogFilterType;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ListAllCatalogsGetController extends AbstractController
{
    private $catalogRepository;
    private $instanceHelper;
    private $instanceManager;
    private $filterManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        InstanceHelper $instanceHelper,
        InstanceManager $instanceManager,
        FilterManager $filterManager
    ) {
        $this->catalogRepository = $entityManager->getRepository(Catalog::class);
        $this->instanceHelper = $instanceHelper;
        $this->instanceManager = $instanceManager;
        $this->filterManager = $filterManager;
    }

    public function __invoke(Request $request): Response
    {
        $instance = $this->instanceHelper->getSessionInstance();
        $directory = $this->instanceManager->getDirectory();

        $filter_form = $this->createForm(
            CatalogFilterType::class,
            null,
            [
                'instance' => $instance,
            ]
        );

        $filter_form->handleRequest($request);
        $query = $this->filterManager
            ->filter(
                $this->catalogRepository->findForInstanceAndGlobal(
                    $instance,
                    $directory
                ),
                $filter_form,
                Catalog::class,
                $instance
            );

        return $this->render(
            'Admin/Catalog/index.html.twig',
            [
                'pagination' => $query->getQuery()->getResult(),
                'filter_form' => $filter_form->createView(),
                'directory' => $directory,
                'instance' => $instance,
            ]
        );
    }
}
