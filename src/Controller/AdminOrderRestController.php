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
use Celsius3\Entity\Institution;
use Celsius3\Entity\Order;
use Celsius3\Entity\State;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Helper\InstanceHelper;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\ViewHandlerInterface;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use Celsius3\Exception\Exception;
use Symfony\Component\Security\Core\Security;

/**
 * User controller.
 *
 * @Route("/admin/rest/orders")
 */
class AdminOrderRestController extends AbstractFOSRestController//BaseInstanceDependentRestController
{

    /**
     * @var Security
     */
    private $security;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var InstanceHelper
     */
    private $instanceHelper;
    /**
     * @var ViewHandlerInterface
     */
    private $viewHandler;
    /**
     * @var Paginator
     */
    private $paginator;
    /**
     * @var ConfigurationHelper
     */
    private $configurationHelper;

    public function __construct(Security $security, EntityManagerInterface $entityManager
    ,InstanceHelper $instanceHelper, ViewHandlerInterface $viewHandler, Paginator $paginator, ConfigurationHelper $configurationHelper)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->instanceHelper = $instanceHelper;
        $this->viewHandler = $viewHandler;
        $this->paginator = $paginator;
        $this->configurationHelper = $configurationHelper;
    }

    protected function getResultsPerPage()
    {
        return $this->configurationHelper
            ->getCastedValue($this->instanceHelper->getSessionOrUrlInstance()->get('results_per_page'));
    }

    protected function getSortDefaults()
    {
        return array(
            'defaultSortFieldName' => 'o.updatedAt',
            'defaultSortDirection' => 'asc',
        );
    }

    /**
     * GET Route annotation.
     *
     * @Get("", name="admin_rest_order", options={"expose"=true})
     */
    public function getOrders(Request $request)
    {
        if ($request->query->get('type', null) === 'mine') {
            $user = $this->security->getUser();
        } else {
            $user = null;
        }

        $state = $request->query->get('state', null);

        $orders = $this->entityManager
                ->getRepository(Order::class)
                ->findForInstance($this->instanceHelper->getSessionInstance(), $user, $state);

        $paginator = $this->paginator;
        $pagination = $paginator->paginate($orders, $this->get('request_stack')->getCurrentRequest()->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */, $this->getSortDefaults())->getItems();

        $view = $this->view(array_values($pagination), 200)->setFormat('json');

        return $this->viewHandler->handle($view);
    }

    /**
     * GET Route annotation.
     *
     * @Get("/count", name="admin_rest_order_count_get", options={"expose"=true})
     */
    public function getOrderCount(Request $request)
    {
        if ($request->query->get('type', null) === 'mine') {
            $user = $this->security->getUser();
        } elseif (is_int(intval($request->query->get('type', null)))) {
            $user = $this->entityManager->getRepository(BaseUser::class)
                    ->find(intval($request->query->get('type', null)));
            if (!is_null($user) && !$user->hasRole('ROLE_ADMIN') && !$user->hasRole('ROLE_SUPER_ADMIN')) {
                $user = null;
            }
        } else {
            $user = null;
        }

        $orderType = $request->query->get('orderType', null);
        if ($orderType === 'allTypes') {
            $orderType = null;
        }

        $orderCount = $this->entityManager
                ->getRepository(State::class)
                ->countOrders($this->instanceHelper->getSessionInstance(), $user, $orderType);

        $view = $this->view($orderCount, 200)->setFormat('json');

        return $this->viewHandler->handle($view);
    }

    /**
     * GET Route annotation.
     *
     * @Get("/get", name="admin_rest_order_request_get", options={"expose"=true})
     */
    public function getOrdersAndRequests(Request $request)
    {
        $em = $this->entityManager;

        if ($request->query->get('type', null) === 'mine') {
            $user = $this->security->getUser();
        } elseif (is_int(intval($request->query->get('type', null)))) {
            $user = $em->getRepository(BaseUser::class)
                    ->find(intval($request->query->get('type', null)));
            if (!is_null($user) && !$user->hasRole('ROLE_ADMIN') && !$user->hasRole('ROLE_SUPER_ADMIN')) {
                $user = null;
            }
        } else {
            $user = null;
        }

        $state = $request->query->get('state', null);
        $orderType = $request->query->get('orderType', null);

        $states = $em->getRepository(Order::class)
                        ->findForInstance($this->instanceHelper->getSessionInstance(), $user, $state, null, $orderType);

        $paginator = $this->paginator;
        $pagination = $paginator->paginate($states, $request->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */, ['sort' => 'createdAt'])->getItems();

        $requests = $em->getRepository(\Celsius3\Entity\Request::class)
                        ->findRequestForOrders($pagination);

        $response = array(
            'orders' => array_values($pagination),
            'requests' => array_column(
                    array_map(
                            function (\Celsius3\Entity\Request $request) {
                                return array(
                                    'id' => $request->getOrder()->getId(),
                                    'request' => $request,
                                );
                            }, $requests), 'request', 'id'),
                );

        $view = $this->view($response, 200)->setFormat('json');

        $context = new Context();
        $context->addGroup('administration_list');
        $view->setContext($context);

        return $this->viewHandler->handle($view);
    }

            /**
             * GET Route annotation.
             *
             * @Get("/{id}", name="admin_rest_order_get", options={"expose"=true})
             */
            public function getOrder($id)
            {
                $em = $this->entityManager;

                $order = $em->getRepository(Order::class)->find($id);

                if (!$order) {
                    throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.order');
                }

                $view = $this->view($order, 200)->setFormat('json');

                $context = new Context();
                $context->addGroup('administration_order_show');
                $view->setContext($context);

                return $this->viewHandler->handle($view);
            }

            /**
             * @Get("/interaction/{id}", name="admin_rest_order_interaction", options={"expose"=true})
             */
            public function getInteraction($id)
            {
                $order = $this->entityManager->getRepository(Order::class)->find($id);
                $institution = $order->getOriginalRequest()->getOwner()->getInstitution();

                $instance = $this->instanceHelper->getSessionInstance();
                $interaction['result'] = false;

                if ($institution->getInstance() !== $instance) {
                    $institutionRepository = $this->entityManager->getRepository(Institution::class);
                    $baseInstitution = $institutionRepository->getBaseInstitution($institution);

                    $interaction['result'] = true;
                    $institutions = $this->entityManager->getRepository(Institution::class)->getInstitutionsTree($baseInstitution);

                    $requestRepository = $this->entityManager->getRepository(\Celsius3\Entity\Request::class);
                    $response['institutionInteraction'] = $requestRepository->getInteractionOfInstitutionWithInstance($instance, $institutions);
                    $response['instanceInteraction'] = $requestRepository->getInteractionOfInstanceWithInstitution($instance, $institutions);

                    $interaction['institution'] = $baseInstitution->getName();
                    $interaction['instance'] = $instance->getName();

                    $interaction['institutionInteraction']['data']['created'] = 0;
                    $interaction['institutionInteraction']['data']['delivered'] = 0;
                    $interaction['institutionInteraction']['data']['annulled'] = 0;
                    $interaction['institutionInteraction']['data']['cancelled'] = 0;
                    foreach ($response['institutionInteraction'] as $res) {
                        $interaction['institutionInteraction']['data'][$res['st']] = $res['c'];
                    }

                    $interaction['instanceInteraction']['data']['created'] = 0;
                    $interaction['instanceInteraction']['data']['delivered'] = 0;
                    $interaction['instanceInteraction']['data']['annulled'] = 0;
                    $interaction['instanceInteraction']['data']['cancelled'] = 0;
                    foreach ($response['instanceInteraction'] as $res) {
                        $interaction['instanceInteraction']['data'][$res['st']] = $res['c'];
                    }
                }

                $view = $this->view($interaction, 200)->setFormat('json');

                return $this->viewHandler->handle($view);
            }

            /**
             * @Get("/operator/{id}", name="admin_rest_order_operator", options={"expose"=true})
             */
            public function getOperator($id)
            {
                $order = $this->entityManager->getRepository(Order::class)->find($id);
                if (!$order) {
                    throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.not_found_entity.order');
                }
                $instance = $this->instanceHelper->getSessionInstance();
                $admins = $this->entityManager->getRepository(BaseUser::class)->findAdmins($instance);
                $interaction['result'] = true;
                $interaction['order'] = $id;
                foreach ($admins as $key => $value) {
                    $interaction['admins'][$key] = $value;
                }
                $view = $this->view($interaction, 200)->setFormat('json');

                return $this->viewHandler->handle($view);
            }

            /**
             * @Get("/change-operator/{order_id}/{id}", name="admin_rest_order_change_operator", options={"expose"=true})
             */
            public function changeOperator($order_id, $id)
            {
                $instance = $this->instanceHelper->getSessionInstance();

                $operator = $this->entityManager->getRepository(BaseUser::class)->find($id);
                $order = $this->entityManager->getRepository(Order::class)->find($order_id);
                if (!$order) {
                    throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.order');
                }

                $request = $this->entityManager->getRepository(\Celsius3\Entity\Request::class)->findOneBy(array('order' => $order, 'instance' => $instance));

                $request->setOperator($operator);
                $em = $this->entityManager;
                $em->persist($request);
                $em->flush();

                $view = $this->view($request, 200)->setFormat('json');

                $context = new Context();
                $context->addGroup('administration_order_show');
                $view->setContext($context);

                return $this->viewHandler->handle($view);
            }
}
