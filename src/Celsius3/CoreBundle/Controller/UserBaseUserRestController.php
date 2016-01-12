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

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use JMS\Serializer\SerializationContext;

/**
 * BaseUser controller.
 *
 * @Route("/user/rest/user")
 */
class UserBaseUserRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("/", name="user_rest_user", options={"expose"=true})
     */
    public function getUsersAction()
    {
        $view = $this->view(array(), 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{id}", name="user_rest_user_get", options={"expose"=true})
     */
    public function getUserAction($id)
    {
        $context = SerializationContext::create()->setGroups(array('user_list'));

        $user = $this->getUser()->getId() === intval($id) ? $this->getUser() : null;

        $view = $this->view($user, 200)->setFormat('json');
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }
}
