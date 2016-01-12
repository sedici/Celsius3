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
                        ->createQueryBuilder('e')
                        ->select('e, r, m')
                        ->join('e.requests', 'r')
                        ->join('e.materialData', 'm');
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

    protected function getSortDefaults()
    {
        return array(
            'defaultSortFieldName' => 'e.updatedAt',
            'defaultSortDirection' => 'asc',
        );
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
        return $this->baseIndex('Order', $this->createForm(OrderFilterType::class));
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
        return $this->baseNew('Order', new Order(), OrderType::class, array(
            'instance' => $this->getDirectory(),
            'user' => $this->getUser(),
            'librarian' => false,
            'actual_user' => $this->getUser(),
        ));
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
        return $this->baseCreate('Order', $entity, OrderType::class, array(
            'instance' => $this->getDirectory(),
            'material' => $this->getMaterialType($entity),
            'user' => $this->getUser(),
            'librarian' => false,
            'actual_user' => $this->getUser(),
        ), 'superadmin_order');
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

        $editForm = $this->createForm(OrderType::class, $entity, array(
            'instance' => $entity->getOriginalRequest()->getInstance(),
            'material' => $this->getMaterialType($materialClass),
            'user' => $this->getUser(),
            'librarian' => false,
            'actual_user' => $this->getUser(),
        ));

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        );
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

        $editForm = $this->createForm(OrderType::class, array(
            'instance' => $entity->getOriginalRequest()->getInstance(),
            'material' => $this->getMaterialType(),
            'user' => $this->getUser(),
            'librarian' => false,
            'actual_user' => $this->getUser(),
        ), $entity);

        $request = $this->get('request_stack')->getCurrentRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('superadmin_order_edit', array('id' => $id)));
        }

        return array('entity' => $entity,
            'edit_form' => $editForm->createView(),
        );
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
