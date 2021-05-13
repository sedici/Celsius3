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

use Celsius3\CoreBundle\Controller\BaseInstanceDependentController;
use Celsius3\CoreBundle\Entity\Catalog;
use Celsius3\CoreBundle\Exception\Exception;
use Celsius3\CoreBundle\Form\Type\CatalogType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

final class EditCatalogGetController extends BaseInstanceDependentController
{
    private $catalogRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->catalogRepository = $entityManager->getRepository(Catalog::class);
    }

    public function __invoke($id): Response
    {
        $entity = $this->catalogRepository
            ->findOneForInstance($this->getInstance(), $id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.catalog');
        }

        $edit_form = $this->createForm(
            CatalogType::class,
            $entity,
            [
                'instance' => $this->getInstance()
            ]
        );

        return $this->render(
            'Admin/Catalog/edit.html.twig',
            [
                'entity' => $entity,
                'edit_form' => $edit_form->createView(),
                'route' => null,
            ]
        );
    }
}
