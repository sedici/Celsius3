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

declare(strict_types=1);

namespace Celsius3\Controller\User\Order;

use Celsius3\Application\User\Order\UserOrderFinder;
use Celsius3\CoreBundle\Exception\EntityNotFoundException;
use Celsius3\Helper\InstanceHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ShowUserOrderViewController extends AbstractController
{
    private $instanceHelper;
    private $userOrderFinder;

    public function __construct(
        InstanceHelper $instanceHelper,
        UserOrderFinder $userOrderFinder
    ) {
        $this->instanceHelper = $instanceHelper;
        $this->userOrderFinder = $userOrderFinder;
    }

    public function __invoke($id)
    {
        $instance = $this->instanceHelper->getSessionInstance();
        $user = $this->getUser();

        try {
            $order = ($this->userOrderFinder)($id, $instance, $user);
        } catch (EntityNotFoundException $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('user_order');
        }

        return $this->render('User/Order/show.html.twig', ['entity' => $order,]);
    }
}
