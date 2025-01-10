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

namespace Celsius3\Controller;

use Celsius3\Entity\Journal;
use Celsius3\Form\Type\JournalTypeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Celsius3\Controller\Base\OrderController;

use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * User order controller.
 *
 * @Route("/user/order")
 */
class UserOrderController extends OrderController
{

    private AuthorizationCheckerInterface $authorizationChecker;
    private $journalRepository;


    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        InstanceManager $instanceManager,
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
        ConfigurationHelper $configurationHelper,
        TranslatorInterface $translator,
        ManagerRegistry $managerRegistry,
        RequestStack $requestStack,
        UnionManager $unionManager,
        UserManager $userManager,
        FilterManager $filterManager,
        InstanceHelper $instanceHelper
    ) {
        parent::__construct(
            $instanceManager,
            $entityManager,
            $paginator,
            $configurationHelper,
            $translator,
            $managerRegistry,
            $requestStack,
            $unionManager,
            $userManager,
            $filterManager,
            $instanceHelper
        );

        $this->authorizationChecker = $authorizationChecker;
        $this->journalRepository = $this->entityManager
            ->getRepository(Journal::class);
    }

    protected final function getTemplatePrefix(): string
    { return 'User/Order/'; }


    /**
     * Lists all user orders.
     *
     * @Route("/", name="user_order")
     */
    public function index(): Response
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

        return $this->baseInstanceIndex(filter_form: $filter_form);
    }


    /**
     * New user order entity.
     *
     * @Route("/{id}/show", name="user_order_show")
     */
    public function show(string $id): Response 
    {
        return $this->baseShow($id);
    }


    /**
     * New user order entity.
     *
     * @Route("/new", name="user_order_new")
     */
    public function new(): Response
    {
        return $this->baseInstanceNew(options: [
            'user' => $this->getUser(),
            'actual_user' => $this->getUser(),
            'librarian' => ($this->authorizationChecker->isGranted(
                UserManager::ROLE_LIBRARIAN
            ))
        ]);
    }


    /**
     * Creates a new user order entity.
     *
     * @Route("/create", name="user_order_create", methods={"POST"})
     */
    public function create(): RedirectResponse|Response
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


        return $this->baseInstanceCreate(
            options: $options, route: 'user_index'
        );
    }


    /**
     * Change user order.
     *
     * @Route("/change", name="user_order_change", options={"expose"=true})
     */
    public function change(): Response
    {
        return parent::change();
    }
}