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

use Celsius3\Form\Type\JournalTypeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\OrderController;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use function get_class;

/**
 * Order controller.
 * @Route("/superadmin/order")
 */
class HtmlSuperadminOrderController extends OrderController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->setInstance($this->directory);
        $this->setInstanceDependent(false);
    }


    public function listQuery(?bool $isInstanceDependent = null): QueryBuilder
    {
        return $this->repository
            ->createQueryBuilder('e')
            ->select('e, r, m')
            ->join('e.requests', 'r')
            ->join('e.materialData', 'm');
    }


    public function findQuery(
        string $id,
        ?bool $isInstanceDependent = null
    ): mixed {
        return $this->repository->find($id);
    }


    // protected function getResultsPerPage()
    // {
    //     return $this->container->getParameter('max_per_page');
    // }


    /**
     * Lists all Order entities.
     * @Route("/", name="superadmin_order")
     */
    public function htmlIndex(): Response
    {
        $this->entityManager->getFilters()->disable('softdeleteable');
        return $this->htmlRenderer->render(
            templateName: 'index',
            params: $this->index()
        );
    }


    /**
     * Finds and displays a Order entity.
     * @Route("/{id}/show", name="superadmin_order_show")
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlShow(string $id): Response
    {
        return $this->htmlRenderer->render(
            templateName: 'show',
            params: $this->show($id)
        );
    }


    /**
     * Displays a form to create a new Order entity.
     * @Route("/new", name="superadmin_order_new")
     */
    public function htmlNew(): Response
    {
        return $this->htmlRenderer->render(
            templateName: 'new',
            params: $this->new(
                formOptions: [
                    'user' => $this->getUser(),
                    'librarian' => false,
                    'actual_user' => $this->getUser()
                ]
            )
        );
    }


    /**
     * Creates a new Order entity.
     * @Route("/create", name="superadmin_order_create", methods={"POST"})
     */
    public function htmlCreate(Request $request): Response
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

        return $this->htmlRenderer->render(
            templateName: 'new',
            params: $this->create(formOptions: $options)
        );
    }


    protected function editFormOptions(
        $entity,
        ?string $type = null,
        ?string $redirectRoute = null,
        ?bool $isInstanceDependent,
        ?array $formExtraOptions = []
    ): array {
        return [
            'material' => $this->getMaterialType(
                get_class($entity->getMaterialData())
            ),
            'user' => $this->getUser(),
            'librarian' => false,
            'actual_user' => $this->getUser(),
        ];
    }


    /**
     * Displays a form to edit an existing Order entity.
     * @Route("/{id}/edit", name="superadmin_order_edit")
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlEdit(string $id): Response
    {
        return $this->htmlRenderer->render(
            templateName: 'edit',
            params: $this->edit($id)
        );

        // $entity = $this->findQuery($id);

        // if (!$entity) $this->error('entity_not_found');

        // return $this->htmlRenderer->render(
        //     templateName: 'edit',
        //     params: [
        //         'entity' => $entity,
        //         'edit_form' => $this->createForm(
        //             data: $entity,
        //             options: [
        //                 'material' => $this->getMaterialType(
        //                     get_class($entity->getMaterialData())
        //                 ),
        //                 'user' => $this->getUser(),
        //                 'librarian' => false,
        //                 'actual_user' => $this->getUser(),
        //             ]
        //         )->createView(),
        //     ]
        // );
    }


    /**
     * Edits an existing Order entity.
     * @Route("/{id}/update", name="superadmin_order_update", methods={"POST"})
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlUpdate(string $id): RedirectResponse|Response
    {
        return $this->htmlRenderer->render(
            templateName: 'edit',
            params: $this->update(
                $id,
                formOptions: [
                    'material' => $this->getMaterialType(),
                    'user' => $this->getUser(),
                    'librarian' => false,
                    'actual_user' => $this->getUser(),
                ]
            )
        );

        // return $this->baseInstanceUpdate(
        //     $id, 'superadmin_order',
        //     options: [
        //         'material' => $this->getMaterialType(),
        //         'user' => $this->getUser(),
        //         'librarian' => false,
        //         'actual_user' => $this->getUser(),
        //     ]
        // );
    }


    /**
     * Updates de form materialData field.
     * @Route("/change", name="superadmin_order_change")
     */
    public function change(): Response
    { return parent::change(); }


    /**
     * SoftDelete an existing Order entity.
     * @Route("/{id}/delete", name="superadmin_order_delete", options={"expose"=true}, methods={"POST"})
     * @param string $id The order ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function softDelete(string $id): JsonResponse
    {
        $order = $this->findQuery($id);

        if (!$order) return new JsonResponse(['success' => false]);

        $em = $this->managerRegistry->getManager();

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
     * @Route("/{id}/undelete", name="superadmin_order_undelete", options={"expose"=true}, methods={"POST"})
     * @param string $id The order ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function softUndelete(string $id): JsonResponse
    {
        $em = $this->entityManager;

        $em->getFilters()->disable('softdeleteable');

        $order = $this->findQuery($id);

        if (!$order) return new JsonResponse(['success' => false]);

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
