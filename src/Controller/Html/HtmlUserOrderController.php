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

namespace Celsius3\Controller\Html;

use Celsius3\Entity\Journal;
use Celsius3\Form\Type\JournalTypeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\OrderController;
use Celsius3\Manager\UserManager;

/**
 * User order controller.
 * @Route("/user/order")
 */
class HtmlUserOrderController extends OrderController
{

    protected $journalRepository;


    public function initialize(): void
    {
        parent::initialize();

        $this->journalRepository = $this->entityManager
            ->getRepository(Journal::class);
        $this->htmlRenderer->setTemplatePrefix('User/Order/');
    }


    /**
     * Lists all user orders.
     * @Route("/", name="user_order")
     */
    public function htmlIndex(): Response
    {
        $request = $this->requestStack->getCurrentRequest();

        $filter_form = $this->createForm(
            options: [ 'owner' => $this->getUser() ]
        );

        $query = $this->repository->listUserOrdersQuery(
            $this->instance,
            $this->getUser()
        );

        if ($filter_form !== null) {
            $filter_form = $filter_form->handleRequest($request);
            $query = $this->filterManager->filter($query, $filter_form, $this->entityClassName);
        }

        return $this->htmlRenderer->render(
            'index',
            $this->index(
                filter_form: $filter_form
            )
        );
    }


    /**
     * New user order entity.
     * @Route("/{id}/show", name="user_order_show")
     */
    public function htmlShow(string $id): Response 
    {
        return $this->htmlRenderer->render(
            'show',
            $this->show($id)
        );
    }


    /**
     * New user order entity.
     * @Route("/new", name="user_order_new")
     */
    public function htmlNew(): Response
    {
        return $this->htmlRenderer->render(
            'new',
            $this->new(
                formOptions: [
                    'user' => $this->getUser(),
                    'librarian' => false,
                    'actual_user' => $this->getUser()
                ]
            )
        );
    }


    /**
     * Creates a new user order entity.
     * @Route("/create", name="user_order_create", methods={"POST"})
     */
    public function htmlCreate(): RedirectResponse|Response
    {
        $request = $this->requestStack->getCurrentRequest();

        $material = $this->getMaterialType();

        $options = [
            'material' => $material,
            'user' => $this->getUser(),
            'actual_user' => $this->getUser(),
            'target' => $request
                ->get('order')['originalRequest']['target'] ?? '',
            'librarian' => $this->authorizationChecker
                ->isGranted(UserManager::ROLE_LIBRARIAN),
        ];

        if ($this->getMaterialType() === JournalTypeType::class)
            $options['other'] = $request->get('order')['materialData']['journal_autocomplete'];


        return $this->htmlRenderer->render(
            'new',
            $this->create(
                formOptions: $options,
            )
        );
    }


    /**
     * Change user order.
     * @Route("/change", name="user_order_change", options={"expose"=true})
     */
    public function change(): Response
    { return parent::change(); }
}