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

declare(strict_types=1);

namespace Celsius3\Controller\Admin\BaseUser;

use Celsius3\Entity\BaseUser;
use Celsius3\Exception\Exception;
use Celsius3\Form\Type\BaseUserType;
use Celsius3\Helper\InstanceHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class EditUserViewController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var InstanceHelper
     */
    private $instanceHelper;

    public function __construct(
        EntityManagerInterface $entityManager,
        InstanceHelper $instanceHelper
    ) {
        $this->entityManager = $entityManager;
        $this->instanceHelper = $instanceHelper;
    }

    public function __invoke($id): Response
    {
        $entity = $this->entityManager
            ->getRepository(BaseUser::class)
            ->findOneForInstance($this->instanceHelper->getSessionOrUrlInstance(), $id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND);
        }

        $editForm = $this->createForm(BaseUserType::class, $entity, ['editing' => true]);

        return $this->render(
            'Admin/BaseUser/edit.html.twig',
            [
                'entity' => $entity,
                'edit_form' => $editForm->createView()
            ]
        );
    }
}
