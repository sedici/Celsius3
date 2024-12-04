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

use Celsius3\Entity\Instance;
use Celsius3\Form\Type\JournalTypeType;
use Celsius3\Helper\ConfigurationHelper;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\Entity\Order;
use Celsius3\Form\Type\OrderType;
use Celsius3\Form\Type\Filter\OrderFilterType;
use Celsius3\Exception\Exception;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    protected function getInstance(): Instance
    { return $this->directory; }

    protected function getTemplatePrefix(): string
    { return 'Superadmin/Order/'; }


    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.updatedAt',
            'defaultSortDirection' => 'asc',
        ];
    }


    protected function listQuery(): QueryBuilder
    {
        return $this->managerRegistry->getManager()
            ->getRepository($this->entityClassName)
            ->createQueryBuilder('e')
            ->select('e, r, m')
            ->join('e.requests', 'r')
            ->join('e.materialData', 'm');
    }


    protected function findQuery(string $id)
    {
        return $this->managerRegistry->getManager()
            ->getRepository($this->entityClassName)
            ->find($id);
    }


    protected function getResultsPerPage()
    {
        return $this->container->getParameter('max_per_page');
    }


    /**
     * Lists all Order entities.
     *
     * @Route("/", name="superadmin_order")
     */
    public function index(): Response
    {
        $this->entityManager->getFilters()->disable('softdeleteable');

        return $this->baseIndex();
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
    public function show(string $id): Response
    {
        return $this->baseShow($id);
    }


    /**
     * Displays a form to create a new Order entity.
     *
     * @Route("/new", name="superadmin_order_new")
     */
    public function new(): Response
    {
        return $this->baseInstanceNew(
            type: OrderType::class,
            options: [
                'user' => $this->getUser(),
                'librarian' => false,
                'actual_user' => $this->getUser()
            ]
        );
    }


    /**
     * Creates a new Order entity.
     *
     * @Route("/create", name="superadmin_order_create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $entityClassName = $this->entityClassName;

        $options = [
            'instance' => $this->instance,
            'material' => $this->getMaterialType(),
            'user' => $this->getUser(),
            'librarian' => false,
            'actual_user' => $this->getUser(),
        ];

        if ($this->getMaterialType() === JournalTypeType::class)
            $options['other'] = $request->request
                ->get('order')['materialData']['journal_autocomplete'];

        return $this->render(
            (string) $this->templatePrefix . 'new.html.twig',
            $this->baseCreateOrderLogic(
                new $entityClassName(),
                $this->typeClassName,
                $options,
                'superadmin_order'
            )
        );
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
    public function edit(string $id): Response
    {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error('entity_not_found');

        return $this->render(
            (string) $this->templatePrefix . 'edit.html.twig',
            [
                'entity' => $entity,
                'edit_form' => $this->createForm(
                    $this->typeClassName,
                    $entity,
                    [
                        'instance' => $this->instance,
                        'material' => $this->getMaterialType(
                            get_class($entity->getMaterialData())
                        ),
                        'user' => $this->getUser(),
                        'librarian' => false,
                        'actual_user' => $this->getUser(),
                    ]
                )->createView(),
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
    public function update(string $id): RedirectResponse|Response
    {
        return $this->baseInstanceUpdate(
            $id, 'superadmin_order',
            options: [
                'material' => $this->getMaterialType(),
                'user' => $this->getUser(),
                'librarian' => false,
                'actual_user' => $this->getUser(),
            ]
        );
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
       * @Route("/{id}/delete", name="superadmin_order_delete", options={"expose"=true}, methods={"POST"})
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
       * @Route("/{id}/undelete", name="superadmin_order_undelete", options={"expose"=true}, methods={"POST"})
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
