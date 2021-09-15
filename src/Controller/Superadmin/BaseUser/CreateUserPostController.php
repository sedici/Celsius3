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

use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\Form\Type\BaseUserType;
use Celsius3\CoreBundle\Manager\InstanceManager;
use Celsius3\Repository\BaseUserRepositoryInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

final class CreateUserPostController extends AbstractController
{
    private $translator;
    private $instanceManager;
    private $baseUserRepository;

    public function __construct(
        TranslatorInterface $translator,
        InstanceManager $instanceManager,
        BaseUserRepositoryInterface $baseUserRepository
    ) {
        $this->translator = $translator;
        $this->instanceManager = $instanceManager;
        $this->baseUserRepository = $baseUserRepository;
    }

    public function __invoke(Request $request): Response
    {
        $name = 'BaseUser';
        $entity = new BaseUser();
        $form = $this->createForm(
            BaseUserType::class,
            $entity,
            [
                'instance' => $this->instanceManager->getDirectory()
            ]
        );
        $form->handleRequest($request);
        if ($form->isValid()) {
            try {
                $this->baseUserRepository->save($entity);

                $this->addFlash(
                    'success',
                    $this->translator->trans(
                        'The %entity% was successfully created.',
                        ['%entity%' => $this->translator->trans($name)],
                        'Flashes'
                    )
                );

                return $this->redirect($this->generateUrl('superadmin_user'));
            } catch (UniqueConstraintViolationException $exception) {
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

        return $this->render('Superadmin/BaseUser/new.html.twig', [
            'entity' => $entity,
            'form' => $form->createView(),
        ]);
    }
}
