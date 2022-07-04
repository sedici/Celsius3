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

use Celsius3\Exception\Exception;
use Celsius3\Form\Type\UserTransformType;
use Celsius3\Manager\UserManager;
use Celsius3\Repository\BaseUserRepositoryInterface;
use Celsius3\Repository\InstanceRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use function array_key_exists;

final class TransformUserViewController extends AbstractController
{
    private $baseUserRepository;
    private $instanceRepository;
    private $userManager;

    public function __construct(
        BaseUserRepositoryInterface $baseUserRepository,
        InstanceRepositoryInterface $instanceRepository,
        UserManager $userManager
    ) {
        $this->baseUserRepository = $baseUserRepository;
        $this->instanceRepository = $instanceRepository;
        $this->userManager = $userManager;
    }

    public function __invoke(Request $request, $id)
    {
        $user = $this->baseUserRepository->find($id);
        if (!$user) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.user');
        }

        if (!$this->getUser()->hasHigherRolesThan($user)) {
            return $this->redirectToRoute('superadmin_user');
        }

        $transformForm = $this->createForm(UserTransformType::class, null, [
            'user' => $user,
            'user_actual' => $this->getUser()
        ]);

        $transformForm->handleRequest($request);
        if ($transformForm->isSubmitted()) {
            if ($transformForm->isValid()) {
                $data = $transformForm->getData();
                $this->userManager->transform($data[$user->getInstance()->getUrl()], $user);

                foreach ($user->getSecondaryInstances() as $key => $value) {
                    $instance = $this->instanceRepository->find($key);

                    if (array_key_exists($instance->getUrl(), $data)) {
                        $user->addSecondaryInstance($instance, $data[$instance->getUrl()]);
                    }
                }

                $this->baseUserRepository->save($user);

                $this->addFlash('success', 'The User was successfully transformed.');

                return $this->redirect($this->generateUrl('superadmin_user_transform', ['id' => $id]));
            }

            $this->addFlash('error', 'There were errors transforming the User.');
        }

        return $this->render('Superadmin/BaseUser/transform.html.twig', [
            'entity' => $user,
            'transform_form' => $transformForm->createView(),
        ]);
    }
}
