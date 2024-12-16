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

namespace Celsius3\Controller\Admin\Catalog;

use Celsius3\Controller\BaseInstanceDependentController;
use Celsius3\Entity\Catalog;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Celsius3\Form\Type\CatalogType;


/**
 * Catalog controller.
 *
 * @Route("/api/v1/admin/catalog")
 */
class CatalogRestController extends BaseInstanceDependentController
{

    protected final function getEntity(): string
    { return Catalog::class; }

    protected final function getType(): string
    { return CatalogType::class; }


    protected final function getTemplatePrefix(): string
    { return 'API/v1/Admin/Catalog/'; }


    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        ];
    }


    protected function index()
    {
        $catalogs = $this->entityManager
            ->getRepository($this->entityClassName)
            ->findForInstanceAndGlobalWithoutDisabled(
                $this->instance, $this->directory
            )
            ->getQuery()
            ->execute();

        $view = $this->view(array_values($catalogs), 200)->setFormat('json');

        $context = new Context();
        $context->addGroup('administration_order_show');
        $view->setContext($context);

        return $this->handleView($view);
    }


    protected function show(string $id)
    {
        $catalog = $this->findQuery($id);

        if ($catalog === null) $this->error('entity_not_found');

        $view = $this->view($catalog, 200)->setFormat('json');

        return $this->handleView($view);
    }
}