<?php

declare(strict_types=1);

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

namespace Celsius3\Controller\User\Order;

use Celsius3\CoreBundle\Entity\State;
use Celsius3\CoreBundle\Helper\InstanceHelper;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\ViewHandlerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

final class OrdersCountGetController extends FOSRestController
{
    private $stateRepository;
    private $instanceHelper;
    private $security;
    private $viewHandler;

    public function __construct(
        EntityManagerInterface $entityManager,
        InstanceHelper $instanceHelper,
        Security $security,
        ViewHandlerInterface $viewHandler
    ) {
        $this->stateRepository = $entityManager->getRepository(State::class);
        $this->instanceHelper = $instanceHelper;
        $this->security = $security;
        $this->viewHandler = $viewHandler;
    }

    public function __invoke(): Response
    {
        $user = $this->security->getUser();

        $order_count = $this->stateRepository->countUserOrders($this->instanceHelper->getSessionInstance(), $user);

        $view = $this->view($order_count, 200)->setFormat('json');

        return $this->viewHandler->handle($view);
    }
}
