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
use Celsius3\CoreBundle\Entity\CatalogPosition;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class UpdateCatalogsPositionsPostController extends BaseInstanceDependentController
{
    private $catalogRepository;
    private $catalogPositionRepository;
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->catalogRepository = $entityManager->getRepository(Catalog::class);
        $this->catalogPositionRepository = $entityManager->getRepository(CatalogPosition::class);
    }

    public function __invoke(Request $request): Response
    {
        $ids = $request->request->get('ids');

        if ($ids) {
            foreach ($ids as $key => $id) {
                $position = $this->catalogPositionRepository
                    ->findOneBy(
                        [
                            'catalog' => $id,
                            'instance' => $this->getInstance()->getId()
                        ]
                    );

                if (!$position) {
                    $position = new CatalogPosition();
                    $position->setEnabled(true);
                    $position->setCatalog($this->catalogRepository->find($id));
                    $position->setInstance($this->getInstance());
                }

                $position->setPosition($key);

                $this->entityManager->persist($position);
            }
            $this->entityManager->flush();
        }

        return new Response(json_encode(['success' => 'Success']));
    }
}
