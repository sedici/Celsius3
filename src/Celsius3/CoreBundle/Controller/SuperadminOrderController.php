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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Entity\Order;
use Celsius3\CoreBundle\Form\Type\OrderType;
use Celsius3\CoreBundle\Filter\Type\OrderFilterType;

/**
 * Order controller.
 *
 * @Route("/superadmin/order")
 */
class SuperadminOrderController extends OrderController
{

    protected function listQuery($name)
    {
        return $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3CoreBundle:' . $name)
                        ->createQueryBuilder('e');
    }

    protected function findQuery($name, $id)
    {
        return $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3CoreBundle:' . $name)
                        ->find($id);
    }

    protected function getResultsPerPage()
    {
        return $this->container->getParameter('max_per_page');
    }

    protected function filter($name, $filter_form, $query)
    {
        return $this->get('celsius3_core.filter_manager')->filter($query, $filter_form, 'Celsius3\\CoreBundle\\Entity\\' . $name);
    }

    /**
     * Lists all Order entities.
     *
     * @Route("/", name="superadmin_order")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('Order', $this->createForm(new OrderFilterType()));
    }

    /**
     * Finds and displays a Order entity.
     *
     * @Route("/{id}/show", name="superadmin_order_show")
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
     * @Route("/new", name="superadmin_order_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('Order', new Order(), new OrderType($this->getDirectory(), null, $this->getUser()));
    }

    /**
     * Creates a new Order entity.
     *
     * @Route("/create", name="superadmin_order_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminOrder:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        $entity = new Order();
        return $this->baseCreate('Order', $entity, new OrderType($this->getDirectory(), $this->getMaterialType($entity), $this->getUser()), 'superadmin_order');
    }

    /**
     * Displays a form to edit an existing Order entity.
     *
     * @Route("/{id}/edit", name="superadmin_order_edit")
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

        $editForm = $this->createForm(new OrderType($entity->getOriginalRequest()->getInstance(), $this->getMaterialType($materialClass), $this->getUser()), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array('entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),);
    }

    /**
     * Edits an existing Order entity.
     *
     * @Route("/{id}/update", name="superadmin_order_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminOrder:edit.html.twig")
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

        $editForm = $this->createForm(new OrderType($entity->getOriginalRequest()->getInstance(), $this->getMaterialType(), $this->getUser()), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->get('request_stack')->getCurrentRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('superadmin_order_edit', array('id' => $id)));
        }

        return array('entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Order entity.
     *
     * @Route("/{id}/delete", name="superadmin_order_delete")
     * @Method("post")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function deleteAction($id)
    {
        return $this->baseDelete('Order', $id, 'superadmin_order');
    }

    /**
     * Updates de form materialData field.
     *
     * @Route("/change", name="superadmin_order_change")
     * @Template()
     *
     * @return array
     */
    public function changeAction()
    {
        return $this->change();
    }
}