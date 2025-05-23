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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\OrderController;
use Celsius3\Manager\MaterialTypeManager;
use Celsius3\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

/**
 * User order controller.
 */
#[
    Route("/user/order"),
    IsGranted('IS_AUTHENTICATED_FULLY')
]
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


    public function listQuery(
        ?bool $isInstanceDependent = null
    ): QueryBuilder {
        return $this->repository->listUserOrdersQuery(
            $this->instance,
            $this->getUser()
        );
    }


    /**
     * Lists all user orders.
     */
    #[Route(
        "/",
        name: 'html_user_order'
    )]
    public function htmlIndex(): Response
    {
        return $this->htmlRenderer->render(
            'index',
            $this->index(
                formOptions: [
                    'owner' => $this->getUser(),
                    // 'type' => ''
                ],
                // hasFilterForm: false,
            )
        );
    }


    /**
     * New user order entity.
     */
    #[Route(
        "/{id}/show",
        name: 'html_user_order_show'
    )]
    public function htmlShow(string $id): Response 
    {
        return $this->htmlRenderer->render(
            'show',
            $this->show($id)
        );
    }


    /**
     * New user order entity.
     */
    #[Route(
        "/new",
        name: 'html_user_order_new'
    )]
    public function htmlNew(): Response
    {
        $user = $this->getUser();

        $params = $this->new(
            formOptions: [
                'user' => $user,
                'librarian' => $this->security->isGranted(
                    UserManager::ROLE_LIBRARIAN
                ),
                'actual_user' => $user
            ]
        );

        return $this->htmlRenderer->render(
            'new', $params
            
        );
    }


    protected function createFormOptions(
        ?string $type = null,
        ?string $redirectRoute = null,
        ?array $formExtraOptions = []
    ): array {
        $request = $this->requestStack->getCurrentRequest();

        $user = $this->getUser();

        $materialName = $request->get(
            'order', null
        )['materialDataType'];

        $materialType = MaterialTypeManager::CLSTYPES_FORM_MAP[$materialName];

        $options = [
            'material' => $materialType,
            'user' => $user,
            'actual_user' => $user,
            'target' => $request
                ->get('order')['originalRequest']['target'] ?? '',
            'librarian' => $this->security
                ->isGranted(UserManager::ROLE_LIBRARIAN)
        ];

        if ($materialType === JournalTypeType::class)
            $options['other'] = $request
                ->get('order')['materialData']['journal_autocomplete'];
        
        return $options;
    }


    /**
     * Creates a new user order entity.
     */
    #[Route(
        "/create",
        name: 'html_user_order_create',
        methods: ["POST"]
    )]
    public function htmlCreate(): RedirectResponse|Response
    {
        $params = $this->create();

        if ($params instanceof Response) return $params;

        return $this->htmlRenderer->render(
            'new', $params
        );
    }


    /**
     * Change user order.
     */
    #[Route(
        "/change",
        name: 'html_user_order_change',
        options: ["expose" => true]
    )]
    public function change(
        ?string $templateName = null,
        ?string $templatePrefix = null,
    ): Response
    {
        return parent::change(
            $templateName,
            $templatePrefix
        );
    }
}