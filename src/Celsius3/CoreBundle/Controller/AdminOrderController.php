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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Entity\Order;
use Celsius3\CoreBundle\Form\Type\OrderType;

/**
 * Order controller.
 *
 * @Route("/admin/order")
 */
class AdminOrderController extends OrderController
{

    protected function listQuery($name)
    {
        return $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3CoreBundle:' . $name)
                        ->findForInstance($this->getInstance());
    }

    protected function findQuery($name, $id)
    {
        return $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3CoreBundle:' . $name)
                        ->findOneForInstance($id, $this->getInstance())->getQuery()
                        ->getSingleResult();
    }

    /**
     * Lists all Order entities.
     *
     * @Route("/", name="admin_order", options={"expose"=true})
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('administration'));
    }

    /**
     * Finds and displays a Order entity.
     *
     * @Route("/{id}/show", name="admin_order_show", options={"expose"=true})
     * @Template()
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function showAction($id)
    {
        return $this->baseShow('Order', $id);
    }

    /**
     * Displays a form to create a new Order entity.
     *
     * @Route("/new", name="admin_order_new", options={"expose"=true})
     * @Template()
     *
     * @return array
     */
    public function newAction(Request $request)
    {
        if ($request->query->has('user_id')) {
            $user = $this->getDoctrine()->getManager()
                    ->getRepository('Celsius3CoreBundle:BaseUser')
                    ->find($request->query->get('user_id'));
        } else {
            $user = null;
        }

        return $this->baseNew('Order', new Order(), new OrderType($this->getInstance(), null, $user, $this->getUser()));
    }

    /**
     * Creates a new Order entity.
     *
     * @Route("/create", name="admin_order_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminOrder:new.html.twig")
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        return $this->baseCreate('Order', new Order(), new OrderType($this->getInstance(), $this->getMaterialType(), null, $this->getUser()), 'administration');
    }

    /**
     * Displays a form to edit an existing Order entity.
     *
     * @Route("/{id}/edit", name="admin_order_edit", options={"expose"=true})
     * @Template()
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function editAction($id)
    {
        $entity = $this->findQuery('Order', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Order.');
        }

        $materialClass = get_class($entity->getMaterialData());
        
        if ($entity->getMaterialData() instanceof \Celsius3\CoreBundle\Entity\JournalType){
            $journal = $entity->getMaterialData()->getJournal();
        } else {
            $journal = null;
        }

        $editForm = $this->createForm(new OrderType($this->getInstance(), $this->getMaterialType($materialClass, $journal), $entity->getOriginalRequest()->getOwner(), $this->getUser()), $entity);

        return array('entity' => $entity,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Displays a form to edit an duplicated Order entity.
     *
     * @Route("/{id}/duplicate", name="admin_order_duplicate", options={"expose"=true})
     * @Method("POST")
     * @Template("Celsius3CoreBundle:AdminOrder:edit.html.twig")
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function duplicateAction($id)
    {
        //Buscar Order
        $order = $this->findQuery('Order', $id);

        if (!$order) {
            throw $this->createNotFoundException('Unable to find Order.');
        }
        
        $entity_manager = $this->getDoctrine()->getManager();
        
        //Clonar Orden original
        $duplicatedOrder = clone $order;
        
        $request = $this->get('celsius3_core.lifecycle_helper')->createRequest($duplicatedOrder, $order->getOriginalRequest()->getOwner(), $order->getOriginalRequest()->getType(), $this->getInstance(), $order->getOriginalRequest()->getCreator());
        $duplicatedOrder->setOriginalRequest($request);
        $duplicatedOrder->setMaterialData(clone $order->getMaterialData());
        
        //Se registra duplicado en la base de datos
        $entity_manager->persist($duplicatedOrder);
        $entity_manager->persist($request);
        $entity_manager->flush();

        $materialClass = get_class($duplicatedOrder->getMaterialData());

        $editForm = $this->createForm(new OrderType($this->getInstance(), $this->getMaterialType($materialClass), $duplicatedOrder->getOriginalRequest()->getOwner(), $this->getUser()), $duplicatedOrder);

        return array(
            'entity' => $duplicatedOrder,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Edits an existing Order entity.
     *
     * @Route("/{id}/update", name="admin_order_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminOrder:edit.html.twig")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function updateAction($id)
    {
        $entity = $this->findQuery('Order', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Order.');
        }

        $entity->setMaterialData(null);

        $request = $this->getRequest();

        // Se extrae el usuario del request y se setea en la construccion del form
        $user = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:BaseUser')
                ->find($request->request->get('celsius3_corebundle_ordertype[originalRequest][owner]', null, true));

        $editForm = $this->createForm(new OrderType($this->getInstance(), $this->getMaterialType(), $user, $this->getUser()), $entity);

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_order_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        );
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
