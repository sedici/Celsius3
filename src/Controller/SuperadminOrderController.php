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

use Celsius3\Form\Type\JournalTypeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Entity\Order;
use Celsius3\Form\Type\OrderType;
use Celsius3\Form\Type\Filter\OrderFilterType;
use Celsius3\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function get_class;

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
                        ->getRepository('Celsius3CoreBundle:'.$name)
                        ->createQueryBuilder('e')
                        ->select('e, r, m')
                        ->join('e.requests', 'r')
                        ->join('e.materialData', 'm');
    }

    protected function findQuery($name, $id)
    {
        return $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3CoreBundle:'.$name)
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
        return $this->get('celsius3_core.filter_manager')->filter($query, $filter_form, 'Celsius3\\CoreBundle\\Entity\\'.$name);
    }

    /**
     * Lists all Order entities.
     *
     * @Route("/", name="superadmin_order")
     */
    public function index(): Response
    {
        $this->getDoctrine()->getManager()->getFilters()->disable('softdeleteable');
        return $this->render(
            'Superadmin/Order/index.html.twig',
            $this->baseIndex('Order', $this->createForm(OrderFilterType::class))
        );
    }

    /**
     * Finds and displays a Order entity.
     *
     * @Route("/{id}/show", name="superadmin_order_show")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function show($id): Response
    {
        return $this->render(
            'Superadmin/Order/show.html.twig',
            $this->baseShow('Order', $id)
        );
    }

    /**
     * Displays a form to create a new Order entity.
     *
     * @Route("/new", name="superadmin_order_new")
     */
    public function new(): Response
    {
        return $this->render(
            'Superadmin/Order/new.html.twig',
            $this->baseNew('Order', new Order(), OrderType::class, [
                'instance' => $this->getDirectory(),
                'user' => $this->getUser(),
                'librarian' => false,
                'actual_user' => $this->getUser(),
            ])
        );
    }

    /**
     * Creates a new Order entity.
     *
     * @Route("/create", name="superadmin_order_create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $entity = new Order();

        $options = array(
            'instance' => $this->getDirectory(),
            'material' => $this->getMaterialType(),
            'user' => $this->getUser(),
            'librarian' => false,
            'actual_user' => $this->getUser(),
        );

        if ($this->getMaterialType() === JournalTypeType::class)
            $options['other'] = $request->request->get('order')['materialData']['journal_autocomplete'];

        return $this->render('Superadmin/Order/new.html.twig', $this->baseCreate('Order', $entity, OrderType::class, $options, 'superadmin_order'));
    }

    /**
     * Displays a form to edit an existing Order entity.
     *
     * @Route("/{id}/edit", name="superadmin_order_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id): Response
    {
        $entity = $this->findQuery('Order', $id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.order');
        }

        $materialClass = get_class($entity->getMaterialData());

        $editForm = $this->createForm(OrderType::class, $entity, [
            'instance' => $entity->getOriginalRequest()->getInstance(),
            'material' => $this->getMaterialType($materialClass),
            'user' => $this->getUser(),
            'librarian' => false,
            'actual_user' => $this->getUser(),
        ]);

        return $this->render(
            'Superadmin/Order/edit.html.twig',
            [
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            ]
        );
    }

    /**
     * Edits an existing Order entity.
     *
     * @Route("/{id}/update", name="superadmin_order_update", methods={"POST"})
     * @param string $id The entity ID
     *
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update($id)
    {
        $entity = $this->findQuery('Order', $id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.order');
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

        return $this->render('Superadmin/Order/edit.html.twig', array('entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Updates de form materialData field.
     *
     * @Route("/change", name="superadmin_order_change")
     */
    public function changeA()
    {
        return $this->change();
    }

      /**
       * SoftDelete an existing Order entity.
       *
       * @Route("/{id}/delete", name="superadmin_order_delete", options={"expose"=true})
       * @Method("post")
       *
       * @param string $id The order ID
       *
       * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
       */
      public function softDelete($id)
      {
          /** @var $order Order */
          $order = $this->findQuery('Order', $id);

          if (!$order) {
              return new JsonResponse(['success' => false]);
          }

          $em = $this->getDoctrine()->getManager();

          $requests = $order->getRequests();

          foreach ($requests as $request) {
              $states = $request->getStates();
              foreach ($states as $state) {
                  $em->remove($state);
              }

              $events = $request->getEvents();
              foreach ($events as $event) {
                  $em->remove($event);
              }

              $em->remove($request);
          }

          $em->remove($order->getMaterialData());
          $em->remove($order);
          $em->flush();

          return new JsonResponse(['success' => true, 'id' => $order->getId()]);
      }

      /**
       * SoftDelete an existing Order entity.
       *
       * @Route("/{id}/undelete", name="superadmin_order_undelete", options={"expose"=true})
       * @Method("post")
       *
       * @param string $id The order ID
       *
       * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
       */
      public function softUndelete($id)
      {
          $this->getDoctrine()->getManager()->getFilters()->disable('softdeleteable');

          $order = $this->findQuery('Order', $id);

          if (!$order) {
              return new JsonResponse(['success' => false]);
          }

          $em = $this->getDoctrine()->getManager();

          $requests = $order->getRequests();
          foreach($requests as $request) {
              $states = $request->getStates();
              foreach ($states as $state) {
                  $em->persist($state->setDeletedAt(null));
              }

              $events = $request->getEvents();
              foreach ($events as $event) {
                  $em->persist($event->setDeletedAt(null));
              }

              $em->persist($request->setDeletedAt(null));
          }

          $em->persist($order->getMaterialData()->setDeletedAt(null));
          $em->persist($order->setDeletedAt(null));
          $em->flush();

          return new JsonResponse(['success' => true, 'id' => $order->getId()]);
      }
}
