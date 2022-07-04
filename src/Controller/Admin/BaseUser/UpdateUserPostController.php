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

use Celsius3\Controller\BaseUserController;
use Celsius3\Entity\BaseUser;
use Celsius3\Exception\Exception;
use Celsius3\Form\Type\BaseUserType;
use Celsius3\Helper\CustomFieldHelper;
use Celsius3\Helper\InstanceHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

final class UpdateUserPostController extends AbstractController
{
    private $entityManager;
    private $customFieldHelper;
    /**
     * @var InstanceHelper
     */
    private $instanceHelper;

    public function __construct(
        EntityManagerInterface $entityManager,
        CustomFieldHelper $customFieldHelper,
        InstanceHelper $instanceHelper
    ) {
        $this->entityManager = $entityManager;
        $this->customFieldHelper = $customFieldHelper;
        $this->instanceHelper = $instanceHelper;
    }

    public function __invoke($id, Request $request)
    {
        $entity = $this->findUser($id);
        $editForm = $this->createForm(
            BaseUserType::class,
            $entity,
            [
                'editing' => true,
                'validation_groups' => ['Profile', 'Default']
            ]
        );

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->updateUser($entity, $editForm);

            return $this->redirect($this->generateUrl('admin_user_edit', ['id' => $id]));
        }

        $this->addFlash('error', 'There were errors editing the BaseUser.');

        return $this->render(
            'Admin/BaseUser/edit.html.twig',
            [
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            ]
        );
    }

    private function findUser($id)
    {
        $entity = $this->entityManager
            ->getRepository(BaseUser::class)
            ->findOneForInstance($this->instanceHelper->getSessionOrUrlInstance(), $id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.user');
        }
        
        return $entity;
    }

    /**
     * @param $entity
     * @param FormInterface $editForm
     */
    private function updateUser($entity, FormInterface $editForm): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $this->customFieldHelper->processCustomUserFields(
            $this->instanceHelper->getSessionOrUrlInstance(),
            $editForm,
            $entity
        );

        $this->addFlash('success', 'The BaseUser was successfully edited.');
    }
}
