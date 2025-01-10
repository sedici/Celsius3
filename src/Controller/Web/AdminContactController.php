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

use Celsius3\Form\Type\AdminContactType;
use Celsius3\Helper\CustomFieldHelper;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\QueryBuilder;
use Celsius3\Controller\Base\ContactController;

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
 * AdminContact controller.
 *
 * @Route("/admin/contact")
 */
class AdminContactController extends ContactController
{

    /**
     * @var CustomFieldHelper
     */
    private $customFieldHelper;

    public function __construct(
        CustomFieldHelper $customFieldHelper,
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
        $this->customFieldHelper = $customFieldHelper;
    }
    
    final protected function getType(): string
    { return AdminContactType::class; }


    protected function listQuery(): QueryBuilder
    {
        return $this->managerRegistry->getManager()
            ->getRepository($this->entityClassName)
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


    protected function getDfaultsPerPage()
    {
        return $this->configurationHelper->getCastedValue(
            $this->instanceHelper
                ->getSessionOrUrlInstance()
                ->get('results_per_page')
        );
    }


    /**
     * Lists all Contact entities.
     *
     * @Route("/", name="admin_contact")
     */
    public function index(): Response
    {
        // $this->baseInstanceIndex();

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

        return $this->render(
            (string) $this->templatePrefix . 'index.html.twig',
            [
                'pagination' => $pagination,
                'deleteForms' => $deleteForms
            ]
        );
    }


    /**
     * Finds and displays a Contact document.
     *
     * @Route("/{id}/show", name="admin_contact_show")
     *
     * @param string $id The document ID
     *
     * @throws NotFoundHttpException If document doesn't exists
     */
    public function show($id): Response
    {
        return $this->baseShow($id);
    }


    /**
     * Displays a form to create a new Contact entity.
     *
     * @Route("/new", name="admin_contact_new")
     */
    public function new(): Response
    {
        $entityClassName = $this->entityClassName;
        $entity = new $entityClassName();
        $entity
            ->setOwningInstance($this->instance)
            ->setInstance($this->instance);
        
        return $this->baseInstanceNew($entity);
    }


    /**
     * Creates a new Contact entity.
     *
     * @Route("/create", name="admin_contact_create", methods={"POST"})
     */
    public function create(): RedirectResponse|Response
    {
        $entityClassName = $this->entityClassName;
        $entity = new $entityClassName();
        $entity
            ->setOwningInstance($this->instance)
            ->setInstance($this->instance);

        return $this->baseInstanceCreate(
            $entity, route: 'admin_contact'
        );
    }


    /**
     * Displays a form to edit an existing Contact entity.
     *
     * @Route("/{id}/edit", name="admin_contact_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id): Response
    {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error('entity_not_found');

        return $this->baseInstanceEdit(
            $id, options: [ 'user' => $entity->getUser() ]
        );
    }


    // Se puede mejorar este controlador
    // Separar los mensajes de error
    // Permitir que se pueda ejecutar una acción processCustom<Entity>Fields para las entidades que correponda

    /**
     * Edits an existing Contact document.
     *
     * @Route("/{id}/update", name="admin_contact_update", methods={"POST"})
     *
     * @param string $id The document ID
     *
     * @throws NotFoundHttpException If document doesn't exists
     */
    public function update(string $id): RedirectResponse|Response
    {

        $entity = $this->findQuery($id);

        if (!$entity) $this->error('entity_not_found');

        $editForm = $this->createForm(
            data: $entity,
            options: [
                'owning_instance' => $this->getInstance(),
                'user' => $entity->getUser(),
            ]
        );

        $request = $this->requestStack->getCurrentRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            try {
                $this->persistEntity($entity);

                $this->customFieldHelper->processCustomContactFields(
                    $this->instance,
                    $editForm,
                    $entity
                );

                $this->addEntityFlash(
                    'success', 'The %entity% was successfully edited.'
                );

                return $this->redirect(
                    $this->generateUrl(
                        'admin_contact_edit',
                        ['id' => $id]
                    )
                );
            } catch (UniqueConstraintViolationException $exception) {
                $this->addEntityFlash(
                    'error', 'The %entity% already exists.'
                );
            }
        }

        $this->addEntityFlash(
            'error', 'There were errors editing the %entity%.'
        );

        return $this->render(
            (string) $this->templatePrefix . 'edit.html.twig',
            [
                'entity' => $entity,
                'edit_form' => $editForm->createView()
            ]
        );
    }

    /**
     * Deletes a Contact document.
     *
     * @Route("/{id}/delete", name="admin_contact_delete", methods={"POST"})
     *
     * @param string $id The document ID
     *
     * @throws NotFoundHttpException If document doesn't exists
     */
    public function delete(string $id)
    {
        $this->baseDelete($id, 'admin_contact');
    }
}
