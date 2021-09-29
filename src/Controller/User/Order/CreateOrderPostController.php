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

namespace Celsius3\Controller\User\Order;

use Celsius3\CoreBundle\Entity\Journal;
use Celsius3\CoreBundle\Entity\Order;
use Celsius3\Form\Type\JournalTypeType;
use Celsius3\Form\Type\OrderType;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class CreateOrderPostController extends AbstractController
{
    private $authorizationChecker;
    private $instanceHelper;
    private $journalRepository;
    private $entityManager;

    public function __construct(
        InstanceHelper $instanceHelper,
        AuthorizationCheckerInterface $authorizationChecker,
        EntityManagerInterface $entityManager
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->instanceHelper = $instanceHelper;
        $this->journalRepository = $entityManager->getRepository(Journal::class);
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request): Response
    {
        $material = $this->getMaterialType($request->request->get('order', null)['materialDataType']);

        $options = [
            'instance' => $this->instanceHelper->getSessionInstance(),
            'material' => $material,
            'user' => $this->getUser(),
            'actual_user' => $this->getUser(),
            'target' => $request->request->get('order')['originalRequest']['target'] ?? '',
            'librarian' => $this->authorizationChecker->isGranted(UserManager::ROLE_LIBRARIAN),
        ];

        if ($material === JournalTypeType::class) {
            $options['other'] = $request->request->get('order')['materialData']['journal_autocomplete'];
        }

        $entity = new Order();
        $form = $this->createForm(OrderType::class, $entity, $options);
        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($material === JournalTypeType::class) {
                $journal = $this->journalRepository->find(
                    $request->request->get('order', null)['materialData']['journal']
                );
                if ($journal === null) {
                    $entity->getMaterialData()->setOther(
                        $request->request->get('order', null)['materialData']['journal_autocomplete']
                    );
                    $entity->getMaterialData()->setJournal(null);
                }
            }

            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            $this->get('session')
                ->getFlashBag()
                ->add('success', 'The order was successfully created.');

            if ($form->has('save_and_show') && $form->get('save_and_show')->isClicked()) {
                return $this->redirect($this->generateUrl('admin_order_show', ['id' => $entity->getId()]));
            }

            return $this->redirect($this->generateUrl('user_index'));
        }

        $this->get('session')
            ->getFlashBag()
            ->add('error', 'There were errors creating the order.');

        return $this->render(
            'User/Order/new.html.twig',
            [
                'entity' => $entity,
                'form' => $form->createView(),
            ]
        );
    }

    private function getMaterialType(string $material): string
    {
        return 'Celsius3\\Form\\Type\\' . ucfirst($material) . 'TypeType';
    }
}
