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

use Celsius3\CoreBundle\Entity\Order;
use Celsius3\Form\Type\OrderType;
use Celsius3\CoreBundle\Helper\InstanceHelper;
use Celsius3\CoreBundle\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class NewOrderViewController extends AbstractController
{
    private $instanceHelper;
    private $authorizationChecker;

    public function __construct(InstanceHelper $instanceHelper, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->instanceHelper = $instanceHelper;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function __invoke(): Response
    {
        $options = [
            'instance' => $this->instanceHelper->getSessionInstance(),
            'user' => $this->getUser(),
            'actual_user' => $this->getUser(),
            'librarian' => ($this->authorizationChecker->isGranted(UserManager::ROLE_LIBRARIAN)),
        ];

        $entity = new Order();

        $form = $this->createForm(OrderType::class, $entity, $options);

        return $this->render(
            'User/Order/new.html.twig',
            [
                'entity' => $entity,
                'form' => $form->createView(),
            ]
        );
    }
}
