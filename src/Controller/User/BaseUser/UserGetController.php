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

namespace Celsius3\Controller\User\BaseUser;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\ViewHandlerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\Security\Core\Security;

final class UserGetController extends FOSRestController
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var ViewHandlerInterface
     */
    private $viewHandler;

    public function __construct(Security $security, ViewHandlerInterface $viewHandler)
    {
        $this->security = $security;
        $this->viewHandler = $viewHandler;
    }

    public function __invoke($id)
    {
        $context = SerializationContext::create()->setGroups(['user_list']);

        $user = $this->security->getUser()->getId() === (int)$id ? $this->security->getUser() : null;

        $view = $this->view($user, 200)->setFormat('json');
        $view->setSerializationContext($context);

        return $this->viewHandler->handle($view);
    }
}