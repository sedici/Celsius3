<?php

/*
 * Celsius3 - Base user management
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

 namespace Celsius3\Controller\Rest;

use Celsius3\Controller\Base\UserController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

 final class RestUserController extends UserController
{
    #[Route(
        '/user/rest/user',
        name: 'user_rest_user',
        methods: ['GET'],
        options: ['expose' => true]
    )]
    public function getUsers(): Response
    { return $this->restRenderer->render([]); }


    #[Route(
        '/user/rest/user/{id}',
        name: 'user_rest_user_get',
        methods: ['GET'],
        options: ['expose' => true]
    )]
    public function getUserById(string $id): Response
    {
        return $this->restRenderer->render(
            $this->getUser() === (int)$id
                ? $this->getUser()
                : null,
            serializerGroups: 'user_list'
        );
    }
}