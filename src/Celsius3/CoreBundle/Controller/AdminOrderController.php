<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

namespace Celsius3\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Document\Order;
use Celsius3\CoreBundle\Form\Type\OrderType;
use Celsius3\CoreBundle\Filter\Type\OrderFilterType;
use Celsius3\CoreBundle\Helper\LifecycleHelper;
use Celsius3\CoreBundle\Document\Request as C3Request;

/**
 * Order controller.
 *
 * @Route("/admin/order")
 */
class AdminOrderController extends OrderController
{

    protected function listQuery($name)
    {
        return $this->getDocumentManager()
                        ->getRepository('Celsius3CoreBundle:' . $name)
                        ->findForInstance($this->getInstance());
    }

    protected function findQuery($name, $id)
    {
        return $this->getDocumentManager()
                        ->getRepository('Celsius3CoreBundle:' . $name)
                        ->findOneForInstance($id, $this->getInstance())->getQuery()
                        ->getSingleResult();
    }

    /**
     * Lists all Order documents.
     *
     * @Route("/", name="admin_order", options={"expose"=true})
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('Order', $this->createForm(new OrderFilterType($this->getInstance())));
    }

    /**
     * Finds and displays a Order document.
     *
     * @Route("/{id}/show", name="admin_order_show", options={"expose"=true})
     * @Template()
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function showAction($id)
    {
        return $this->baseShow('Order', $id);
    }

    /**
     * Displays a form to create a new Order document.
     *
     * @Route("/new", name="admin_order_new", options={"expose"=true})
     * @Template()
     *
     * @return array
     */
    public function newAction(Request $request)
    {
        $user = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:BaseUser')
                ->find($request->query->get('user_id', null));

        return $this->baseNew('Order', new Order(), new OrderType($this->getInstance(), null, $user, $this->getUser()));
    }

    /**
     * Creates a new Order document.
     *
     * @Route("/create", name="admin_order_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminOrder:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Order', new Order(), new OrderType($this->getInstance(), $this->getMaterialType(), null, $this->getUser()), 'admin_order');
    }

    /**
     * Displays a form to edit an existing Order document.
     *
     * @Route("/{id}/edit", name="admin_order_edit", options={"expose"=true})
     * @Template()
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id)
    {
        $document = $this->findQuery('Order', $id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Order.');
        }

        $materialClass = get_class($document->getMaterialData());

        $editForm = $this->createForm(new OrderType($this->getInstance(), $this->getMaterialType($materialClass), $document->getOriginalRequest()->getOwner(), $this->getUser()), $document);
        $deleteForm = $this->createDeleteForm($id);

        return array('document' => $document,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),);
    }

    /**
     * Displays a form to edit an duplicated Order document.
     *
     * @Route("/{id}/duplicate", name="admin_order_duplicate", options={"expose"=true})
     * @Method("POST")
     * @Template("Celsius3CoreBundle:AdminOrder:edit.html.twig")
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function duplicateAction($id)
    {
        //Buscar Order
        $order = $this->findQuery('Order', $id);

        if (!$order) {
            throw $this->createNotFoundException('Unable to find Order.');
        }

        //Clonar Orden original
        $duplicatedOrder = clone $order;

        $request = $this->get('celsius3_core.lifecycle_helper')->createRequest($duplicatedOrder, $order->getOriginalRequest()->getOwner(), $order->getOriginalRequest()->getType(), $this->getInstance());
        $duplicatedOrder->setOriginalRequest($request);

        //Se registra duplicado en la base de datos
        $document_manager = $this->getDocumentManager();
        $document_manager->persist($duplicatedOrder);
        $document_manager->flush();


        $materialClass = get_class($duplicatedOrder->getMaterialData());

        $editForm = $this->createForm(new OrderType($this->getInstance(), $this->getMaterialType($materialClass), $duplicatedOrder->getOriginalRequest()->getOwner(), $this->getUser()), $duplicatedOrder);
        $deleteForm = $this->createDeleteForm($id);

        return array('document' => $duplicatedOrder,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),);
    }

    /**
     * Edits an existing Order document.
     *
     * @Route("/{id}/update", name="admin_order_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminOrder:edit.html.twig")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        $document = $this->findQuery('Order', $id);

        if (!$document) {
            throw $this
                    ->createNotFoundException('Unable to find ' . 'Order' . '.');
        }

        $document->setMaterialData(null);

        $request = $this->getRequest();

        // Se extrae el usuario del request y se setea en la construccion del form
        $user = $this->getDocumentManager()->getRepository('Celsius3CoreBundle:BaseUser')
                ->find($request->request->get('celsius3_corebundle_ordertype[originalRequest][owner]', null, true));

        $editForm = $this->createForm(new OrderType($this->getInstance(), $this->getMaterialType(), $user, $this->getUser()), $document);
        $deleteForm = $this->createDeleteForm($id);

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $dm = $this->getDocumentManager();
            $dm->persist($document);
            $dm->flush();

            return $this->redirect($this->generateUrl('admin_order_edit', array('id' => $id)));
        }

        return array('document' => $document,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),);
    }

    /**
     * Updates de form materialData field.
     *
     * @Route("/change", name="admin_order_change", options={"expose"=true})
     * @Template()
     *
     * @return array
     */
    public function changeAction()
    {
        return $this->change();
    }

}
