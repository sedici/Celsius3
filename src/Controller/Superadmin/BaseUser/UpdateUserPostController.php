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
use Celsius3\Form\Type\BaseUserType;
use Celsius3\CoreBundle\Manager\InstanceManager;
use Celsius3\Repository\BaseUserRepositoryInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Translation\TranslatorInterface;

final class UpdateUserPostController extends AbstractController
{
    private $instanceManager;
    private $translator;
    private $baseUserRepository;

    public function __construct(
        InstanceManager $instanceManager,
        TranslatorInterface $translator,
        BaseUserRepositoryInterface $baseUserRepository
    ) {
        $this->instanceManager = $instanceManager;
        $this->translator = $translator;
        $this->baseUserRepository = $baseUserRepository;
    }

    public function __invoke($id)
    {
        $user = $this->baseUserRepository->find($id);

        if (!$user) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.BaseUser');
        }

        $editForm = $this->createForm(BaseUserType::class, $user, [
            'instance' => $this->instanceManager->getDirectory(),
            'editing' => true,
        ]);

        $request = $this->get('request_stack')->getCurrentRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            try {
                $this->baseUserRepository->save($user);

                $this->addFlash(
                    'success',
                    $this->translator->trans(
                        'The %entity% was successfully edited.',
                        ['%entity%' => $this->translator->trans('BaseUser')],
                        'Flashes'
                    )
                );

                return $this->redirect($this->generateUrl('superadmin_user_edit', ['id' => $id]));
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash(
                    'error',
                    $this->translator->trans(
                        'The %entity% already exists.',
                        ['%entity%' => $this->translator->trans('BaseUser')],
                        'Flashes'
                    )
                );
            }
        }

        $this->addFlash(
            'error',
            $this->translator->trans(
                'There were errors editing the %entity%.',
                ['%entity%' => $this->translator->trans('BaseUser')],
                'Flashes'
            )
        );

        return $this->render('Superadmin/BaseUser/edit.html.twig', [
            'entity' => $user,
            'edit_form' => $editForm->createView(),
        ]);
    }
}
