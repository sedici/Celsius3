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

use Celsius3\Helper\CustomFieldHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\QueryBuilder;
use Celsius3\Controller\Base\ContactController;
use Celsius3\Controller\Core\HtmlRenderer;
use Celsius3\Controller\Core\RestRenderer;
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
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * AdminContact controller.
 * @Route("/admin/contact")
 */
class HtmlAdminContactController extends ContactController
{

    public function __construct(
        protected CustomFieldHelper $customFieldHelper,
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
        InstanceHelper $instanceHelper,
        FormFactoryInterface $formFactory,
        FlashBagInterface $session,
        RouterInterface $router,
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker,
        HtmlRenderer $htmlRenderer,
        RestRenderer $restRenderer
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
            $instanceHelper,
            $formFactory,
            $session,
            $router,
            $tokenStorage,
            $authorizationChecker,
            $htmlRenderer,
            $restRenderer
        );

        $this->customFieldHelper = $customFieldHelper;
        $this->initialize();
    }


    public function listQuery(?bool $isInstanceDependent = null): QueryBuilder
    {
        return $this->repository
            ->createQueryBuilder('e')
            ->select('e')
            ->where('e.owningInstance = :instance')
            ->setParameter(
                'instance',
                $this->instanceHelper
                    ->getSessionOrUrlInstance()
                    ->getId()
            );
    }


    /**
     * Lists all Contact entities.
     * @Route("/", name="admin_contact")
     */
    public function htmlIndex(): Response
    {
        $query = $this->listQuery();
        $request = $this->requestStack->getCurrentRequest();

        $pagination = $this->paginator->paginate(
            $query,
            intval($request->query->get('page', 1)),
            $this->getResultsPerPage(),
            $this->sortDefaults
        );

        $deleteForms = [];
        foreach ($pagination as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm(
                $entity->getId()
            )->createView();
        }

        return $this->htmlRenderer->render(
            templateName: 'index',
            params: [
                'pagination' => $pagination,
                'deleteForms' => $deleteForms
            ]
        );
    }


    /**
     * Finds and displays a Contact document.
     * @Route("/{id}/show", name="admin_contact_show")
     * @param string $id The document ID
     * @throws NotFoundHttpException If document doesn't exists
     */
    public function htmlShow($id): Response
    {
        return $this->htmlRenderer->render(
            templateName: 'show',
            params: $this->show($id)
        );
    }


    /**
     * Displays a form to create a new Contact entity.
     * @Route("/new", name="admin_contact_new")
     */
    public function htmlNew(): Response
    {
        $entityClassName = $this->entityClassName;
        $entity = new $entityClassName();
        $entity
            ->setOwningInstance($this->instance)
            ->setInstance($this->instance);
        
        return $this->htmlRenderer->render(
            templateName: 'new',
            params: $this->create($entity)
        );
    }


    /**
     * Creates a new Contact entity.
     * @Route("/create", name="admin_contact_create", methods={"POST"})
     */
    public function htmlCreate(): RedirectResponse|Response
    {
        $entityClassName = $this->entityClassName;
        $entity = new $entityClassName();
        $entity
            ->setOwningInstance($this->instance)
            ->setInstance($this->instance);

        return $this->htmlRenderer->render(
            templateName: 'new',
            params: $this->create($entity)
        );
    }


    /**
     * Displays a form to edit an existing Contact entity.
     * @Route("/{id}/edit", name="admin_contact_edit")
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlEdit($id): Response
    {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error('entity_not_found');

        return $this->htmlRenderer->render(
            templateName: 'edit',
            params: $this->edit($entity, formOptions: [ 'user' => $entity->getUser() ])
        );
    }


    protected function updateFormOptions(
        $entity,
        ?string $type = null,
        ?string $redirectRoute = null,
        ?bool $isInstanceDependent,
        ?array $formExtraOptions = []
    ): array {
        return [
            'owning_instance' => $entity->instance,
            'user' => $entity->getUser()
        ];
    }


    protected function onValidUpdateForm(
        $entity,
        FormInterface $form,
        array $formOptions,
        string $redirectRoute,
        Request $request
    ): void {
        $this->persistEntity($entity);

        $this->customFieldHelper->processCustomContactFields(
            $this->instance,
            $form,
            $entity
        );
    }


    /**
     * Edits an existing Contact document.
     * @Route("/{id}/update", name="admin_contact_update", methods={"POST"})
     * @param string $id The document ID
     * @throws NotFoundHttpException If document doesn't exists
     */
    public function htmlUpdate(string $id): RedirectResponse|Response
    {
        return $this->htmlRenderer->render(
            templateName: 'edit',
            params: $this->update($id)
        );
    }


    /**
     * Deletes a Contact document.
     * @Route("/{id}/delete", name="admin_contact_delete", methods={"POST"})
     * @param string $id The document ID
     * @throws NotFoundHttpException If document doesn't exists
     */
    public function htmlDelete(string $id)
    {
        return $this->htmlRenderer->render(
            templateName: 'delete',
            params: $this->delete($id)
        );
    }
}
