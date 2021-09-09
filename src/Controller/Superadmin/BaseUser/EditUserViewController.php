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

namespace Celsius3\Controller\Superadmin\BaseUser;

use Celsius3\CoreBundle\Exception\Exception;
use Celsius3\CoreBundle\Form\Type\BaseUserType;
use Celsius3\CoreBundle\Manager\InstanceManager;
use Celsius3\Repository\BaseUserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class EditUserViewController extends AbstractController
{
    private $baseUserRepository;
    private $instanceManager;

    public function __construct(
        BaseUserRepositoryInterface $baseUserRepository,
        InstanceManager $instanceManager
    ) {
        $this->baseUserRepository = $baseUserRepository;
        $this->instanceManager = $instanceManager;
    }

    public function __invoke($id)
    {
        $entity = $this->baseUserRepository->find($id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.BaseUser');
        }

        $editForm = $this->createForm(BaseUserType::class, $entity, [
            'instance' => $this->instanceManager->getDirectory(),
            'editing' => true,
        ]);

        return $this->render('Superadmin/BaseUser/edit.html.twig', [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ]);
    }
}
