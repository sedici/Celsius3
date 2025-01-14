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

use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Journal;
use Celsius3\Entity\JournalType;
use Celsius3\Entity\Order;
use Celsius3\Form\Type\JournalTypeType;
use Celsius3\Helper\LifecycleHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
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
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;

use function get_class;

/**
 * Order controller.
 *
 * @Route("/admin/order")
 */
class AdminOrderController extends OrderController
{

    private $lifecycleHelper;

    public function __construct(
        LifecycleHelper $lifecycleHelper,
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

        $this->lifecycleHelper = $lifecycleHelper;
    }


    protected function listQuery(): QueryBuilder
    {
        return $this->entityManager
            ->getRepository($this->entityClassName)
            ->findForInstance($this->instance);
    }


    /**
     * Lists all Order entities.
     *
     * @Route("/", name="admin_order", options={"expose"=true})
     */
    public function index(): RedirectResponse
    {
        return $this->redirect($this->generateUrl('administration'));
    }


    /**
     * Finds and displays a Order entity.
     *
     * @Route("/{id}/show", name="admin_order_show", options={"expose"=true})
     *
     * @param  string  $id  The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function show($id): Response
    {
        return $this->baseShow($id);
    }


    /**
     * Displays a form to create a new Order entity.
     *
     * @Route("/new", name="admin_order_new", options={"expose"=true})
     *
     */
    public function new(): Response
    {
        $request = $this->requestStack->getCurrentRequest();

        $user = ($request->query->has('user_id'))
            ? $this->entityManager
                ->getRepository(BaseUser::class)
                ->find($request->query->get('user_id'))
            : null;

        return $this->baseInstanceNew(
            options: [
                'user' => $user,
                'operator' => $this->getUser(),
                'actual_user' => $this->getUser(),
                'create' => true,
            ]
        );
    }


    /**
     * Creates a new Order entity.
     *
     * @Route("/create", name="admin_order_create", methods={"POST"})
     */
    public function create(): RedirectResponse|Response
    {
        $request = $this->requestStack->getCurrentRequest();

        $materialType = $this->getMaterialTypeClassName(
            $request->request->get('order', null)
        );

        $options = [
            'material' => $materialType,
            'operator' => $this->getUser(),
            'actual_user' => $this->getUser(),
            'create' => true,
            'user' => $this->entityManager
                ->getRepository(BaseUser::class)
                ->find($request->request->get('order')['originalRequest']['owner']),
        ];

        if ($materialType === JournalTypeType::class) {
            $options['other'] = $request->request->get('order')['materialData']['journal_autocomplete'];
            $options['journal_id'] = $request->request->get('order')['materialData']['journal'];
        }

        $order = new Order();
        $route = 'administration';

        $form = $this->createForm(data: $order, formOptions: $options);
        $form->handleRequest($request);

        if (!$order->getOriginalRequest()->getOwner()) {
            $form->get('originalRequest')
                ->get('owner_autocomplete')
                ->addError(new FormError('El usuario seleccionado no es válido'));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            if ($materialType === JournalTypeType::class) {
                $journal = $this->entityManager->getRepository(Journal::class)->find(
                    $request->request->get('order')['materialData']['journal']
                );
                if ($journal === null) {
                    $order->getMaterialData()->setOther(
                        $request->request->get('order')['materialData']['journal_autocomplete']
                    );
                    $order->getMaterialData()->setJournal(null);
                }
            }

            $this->persistEntity($order);

            $this->addFlash('success', 'The Order was successfully created.');

            if ($form->has('save_and_show')) {
                $saveNShow = $form->get('save_and_show');
                if (
                    $saveNShow instanceof SubmitButton
                    && $saveNShow->isClicked()
                ) {
                    return $this->redirect($this->generateUrl(
                        'admin_order_show', ['id' => $order->getId()])
                    );
                }
            }

            return $this->redirect($this->generateUrl($route));
        }

        $this->addFlash('error', 'There were errors creating the Order.');

        return $this->render(
            (string) $this->templatePrefix . 'new.html.twig',
            [
                'entity' => $order,
                'form' => $form->createView(),
            ]
        );
    }


    /**
     * Displays a form to edit an existing Order entity.
     *
     * @Route("/{id}/edit", name="admin_order_edit", options={"expose"=true})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id): Response
    {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error('entity_not_found');

        $materialClass = get_class($entity->getMaterialData());

        $journal = ($entity->getMaterialData() instanceof JournalType)
            ? $entity->getMaterialData()->getJournal()
            : null;

        $other = ($entity->getMaterialData() instanceof JournalType)
            ? $entity->getMaterialData()->getOther() : '';

        $editForm = $this->createForm(
            data: $entity,
            formOptions: [
                'material' => $this->getMaterialType($materialClass),
                'user' => $entity->getOriginalRequest()->getOwner(),
                'operator' => $this->getUser(),
                'actual_user' => $this->getUser(),
                'journal' => $journal,
                'other' => $other,
                'journal_id' => $journal !== null ? $journal->getId() : '',

            ]
        );

        return $this->render(
            (string) $this->templatePrefix . 'edit.html.twig',
            [
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            ]
        );
    }


    /**
     * Displays a form to edit an duplicated Order entity.
     *
     * @Route("/{id}/duplicate", name="admin_order_duplicate", options={"expose"=true}, methods={"POST"})
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function duplicate($id): Response
    {
        $order = $this->findQuery($id);

        if (!$order) $this->error('entity_not_found');

        //Clonar Orden original
        $duplicatedOrder = clone $order;

        $request =  $this->lifecycleHelper->createRequest(
            $duplicatedOrder,
            $order->getOriginalRequest()->getOwner(),
            $order->getOriginalRequest()->getType(),
            $this->getInstance(),
            $order->getOriginalRequest()->getCreator()
        );
        $duplicatedOrder->setOriginalRequest($request);
        $duplicatedMaterialData = clone $order->getMaterialData();
        $duplicatedOrder->setMaterialData($duplicatedMaterialData);

        $journal = ($duplicatedMaterialData instanceof JournalType)
            ? $duplicatedMaterialData->getJournal()
            : null;

        $other = ($duplicatedMaterialData instanceof JournalType)
            ? $duplicatedMaterialData->getOther() : '';

            //Se registra duplicado en la base de datos
        $this->persistEntity($duplicatedOrder);
        $this->persistEntity($request);

        $materialClass = get_class($duplicatedOrder->getMaterialData());

        $editForm = $this->createForm(
            data: $duplicatedOrder,
            formOptions: [
                'material' => $this->getMaterialType($materialClass),
                'user' => $duplicatedOrder->getOriginalRequest()->getOwner(),
                'operator' => $this->getUser(),
                'actual_user' => $this->getUser(),
                'journal' => $journal,
                'other' => $other,
            ]
        );

        return $this->render(
            (string) $this->templatePrefix . 'edit.html.twig',
            [
                'entity' => $duplicatedOrder,
                'edit_form' => $editForm->createView(),
            ]
        );
    }


    /**
     * Edits an existing Order entity.
     *
     * @Route("/{id}/update", name="admin_order_update", methods={"POST"})
     *
     * @param  string  $id  The entity ID
     *
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update($id): RedirectResponse|Response
    {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error('entity_not_found');

        $entity->setMaterialData(null);

        $request = $this->requestStack->getCurrentRequest();

        // Se extrae el usuario del request y se setea en la construccion del form
        $user = $this->entityManager
            ->getRepository(BaseUser::class)
            ->find($request->request->get(
                'order', null
            )['originalRequest']['owner']);

        $editForm = $this->createForm(
            data: $entity,
            formOptions: [
                'material' => $this->getMaterialType(),
                'user' => $user,
                'operator' => $this->getUser(),
                'actual_user' => $this->getUser(),
            ]
        );

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if ($this->getMaterialType() === 'Celsius3\Form\Type\JournalTypeType') {
                $journal = $this->entityManager->getRepository(Journal::class)->find(
                    $request->request->get('order', null)['materialData']['journal']
                );
                if ($journal === null) {
                    $entity->getMaterialData()->setOther(
                        $request->request->get('order', null)['materialData']['journal_autocomplete']
                    );
                    $entity->getMaterialData()->setJournal(null);
                }
            }

            $this->persistEntity($entity);

            if ($editForm->has('save_and_show')) {
                $saveNShow = $editForm->get('save_and_show');
                if (
                    $saveNShow instanceof SubmitButton
                    && $saveNShow->isClicked()
                ) {
                    return $this->redirect($this->generateUrl(
                        'admin_order_show', ['id' => $id]
                    ));
                }
            }

            return $this->redirect($this->generateUrl(
                'admin_order_edit', ['id' => $id]
            ));
        }

        return $this->render(
            (string) $this->templatePrefix . 'edit.html.twig',
            [
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            ]
        );
    }


    /**
     * Updates de form materialData field.
     *
     * @Route("/change", name="admin_order_change", options={"expose"=true})
     */
    public function change(): Response
    {
        return parent::change();
    }
}
