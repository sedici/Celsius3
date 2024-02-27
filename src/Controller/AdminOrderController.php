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

use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Journal;
use Celsius3\Entity\JournalType;
use Celsius3\Entity\Order;
use Celsius3\Exception\Exception;
use Celsius3\Form\Type\Filter\CityFilterType;
use Celsius3\Form\Type\JournalTypeType;
use Celsius3\Form\Type\OrderType;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Helper\LifecycleHelper;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Helper\ConfigurationHelper;
use Symfony\Contracts\Translation\TranslatorInterface;
use Celsius3\Manager\InstanceManager;
use Symfony\Component\HttpFoundation\Response;
use function get_class;

/**
 * Order controller.
 *
 * @Route("/admin/order")
 */
class AdminOrderController extends OrderController
{
    /**
     * @var InstanceHelper
     */
    private $instanceHelper;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var InstanceManager
     */
    private $instanceManager;

    /**
     * @var PaginatorInterface
     */
    private $paginator;
    /**
     * @var ConfigurationHelper
     */
    private $configurationHelper;
    /**
     * @var Translator
     */
    private $translator;

    private $lifecycleHelper;
    public function __construct(
        PaginatorInterface $paginator,
        ConfigurationHelper $configurationHelper,
        InstanceHelper $instanceHelper,
        TranslatorInterface $translator,
        InstanceManager $instanceManager,
        EntityManagerInterface $entityManager,
        LifecycleHelper $lifecycleHelper

    ) {
        $this->paginator = $paginator;
        $this->configurationHelper=$configurationHelper;
        $this->setIntanceHelper($instanceHelper);
        $this->setConfigurationHelper($configurationHelper);
        $this->translator=$translator;
        $this->setTranslator($translator);
        $this->instanceManager=$instanceManager;
        $this->instanceHelper = $instanceHelper;
        $this->entityManager = $entityManager;
        $this->lifecycleHelper = $lifecycleHelper;
    }




    protected function baseNew($name, $entity, $type, array $options = array())
    {
        $form = $this->createForm($type, $entity, $options);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }
    
    protected function getSortDefaults()
    {
        return [
            'defaultSortFieldName' => 'o.updatedAt',
            'defaultSortDirection' => 'asc',
        ];
    }

    protected function listQuery($name)
    {
        return $this->entityManager
            ->getRepository('Celsius3:'.$name)
            ->findForInstance($this->getInstance());
    }

    protected function findQuery($name, $id)
    {
        return $this->entityManager
            ->getRepository(Order::class)
            ->findOneForInstance($this->getInstance(), $id);
    }

    /**
     * Lists all Order entities.
     *
     * @Route("/", name="admin_order", options={"expose"=true})
     */
    public function index()
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
    public function show($id)
    {

     //   return $this->baseShow('Order', $id);
return $this->render(
'Admin/Order/show.html.twig',
$this->baseShow('Order', $id)
);

    }

    /**
     * Displays a form to create a new Order entity.
     *
     * @Route("/new", name="admin_order_new", options={"expose"=true})
     *
     */
    public function new(Request $request)
    {
        if ($request->query->has('user_id')) {
            $user = $this->entityManager
                ->getRepository(BaseUser::class)
                ->find($request->query->get('user_id'));
        } else {
            $user = null;
        }

        return $this->render('Admin/Order/new.html.twig', $this->baseNew(
            'Order',
            new Order(),
            OrderType::class,
            [
                'instance' => $this->getInstance(),
                'user' => $user,
                'operator' => $this->getUser(),
                'actual_user' => $this->getUser(),
                'create' => true,
            ]
        ));
    }

    /**
     * Creates a new Order entity.
     *
     * @Route("/create", name="admin_order_create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $materialType = 'Celsius3\\Form\\Type\\' . ucfirst($request->request->get('order', null, true)['materialDataType']) . 'TypeType';
        $options = [
            'instance' => $this->getInstance(),
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
        $type = OrderType::class;
        $route = 'administration';

        $form = $this->createForm($type, $order, $options);
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

            $this->entityManager->persist($order);
            $this->entityManager->flush();

            $this->get('session')
                ->getFlashBag()
                ->add('success', 'The Order was successfully created.');

            if ($form->has('save_and_show') && $form->get('save_and_show')->isClicked()) {
                return $this->redirect($this->generateUrl('admin_order_show', ['id' => $order->getId()]));
            }

            return $this->redirect($this->generateUrl($route));
        }

        $this->get('session')
            ->getFlashBag()
            ->add('error', 'There were errors creating the Order.');

        return $this->render(
            'Admin/Order/new.html.twig',
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
    public function edit($id)
    {
        $entity = $this->findQuery('Order', $id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.order');
        }

        $materialClass = get_class($entity->getMaterialData());

        if ($entity->getMaterialData() instanceof JournalType) {
            $journal = $entity->getMaterialData()->getJournal();
        } else {
            $journal = null;
        }

        $other = ($entity->getMaterialData(
            ) instanceof JournalType) ? $entity->getMaterialData()->getOther() : '';

        $editForm = $this->createForm(
            OrderType::class,
            $entity,
            [
                'instance' => $this->getInstance(),
                'material' => $this->getMaterialType($materialClass),
                'user' => $entity->getOriginalRequest()->getOwner(),
                'operator' => $this->getUser(),
                'actual_user' => $this->getUser(),
                'journal' => $journal,
                'other' => $other,
                'journal_id' => $journal !== null ? $journal->getId() : '',

            ]
        );


        return $this->render('Admin/Order/edit.html.twig', [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an duplicated Order entity.
     *
     * @Route("/{id}/duplicate", name="admin_order_duplicate", options={"expose"=true}, methods={"POST"})
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function duplicate($id)
    {
        $order = $this->findQuery('Order', $id);

        if (!$order) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.order');
        }

        $entity_manager = $this->entityManager;

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

        if ($duplicatedMaterialData instanceof JournalType) {
            $journal = $duplicatedMaterialData->getJournal();
        } else {
            $journal = null;
        }

        $other = ($duplicatedMaterialData instanceof JournalType) ? $duplicatedMaterialData->getOther(
        ) : '';

        //Se registra duplicado en la base de datos
        $entity_manager->persist($duplicatedOrder);
        $entity_manager->persist($request);
        $entity_manager->flush();

        $materialClass = get_class($duplicatedOrder->getMaterialData());

        $editForm = $this->createForm(
            OrderType::class,
            $duplicatedOrder,
            [
                'instance' => $this->getInstance(),
                'material' => $this->getMaterialType($materialClass),
                'user' => $duplicatedOrder->getOriginalRequest()->getOwner(),
                'operator' => $this->getUser(),
                'actual_user' => $this->getUser(),
                'journal' => $journal,
                'other' => $other,
            ]
        );

        return $this->render('Admin/Order/edit.html.twig', [
            'entity' => $duplicatedOrder,
            'edit_form' => $editForm->createView(),
        ]);
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
    public function update($id, Request $request)
    {
        $entity = $this->findQuery('Order', $id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.order');
        }

        $entity->setMaterialData(null);

        // Se extrae el usuario del request y se setea en la construccion del form
        $user = $this->entityManager->getRepository(BaseUser::class)
            ->find($request->request->get('order', null)['originalRequest']['owner']);

        $editForm = $this->createForm(
            OrderType::class,
            $entity,
            [
                'instance' => $this->getInstance(),
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
                if (is_null($journal)) {
                    $entity->getMaterialData()->setOther(
                        $request->request->get('order', null)['materialData']['journal_autocomplete']
                    );
                    $entity->getMaterialData()->setJournal(null);
                }
            }
            $em = $this->entityManager;
            $em->persist($entity);
            $em->flush();

            if ($editForm->has('save_and_show')) {
                if ($editForm->get('save_and_show')->isClicked()) {
                    return $this->redirect($this->generateUrl('admin_order_show', ['id' => $id]));
                }
            }

            return $this->redirect($this->generateUrl('admin_order_edit', ['id' => $id]));
        }

        return $this->render('Admin/Order/edit.html.twig', [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ]);
    }

    /**
     * Updates de form materialData field.
     *
     * @Route("/change", name="admin_order_change", options={"expose"=true})
     */
    public function changeA()
    {
        return $this->change();
    }
}
