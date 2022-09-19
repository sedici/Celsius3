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

namespace Celsius3\Controller\Admin\BaseUser;

use Celsius3\Controller\BaseUserController;
use Celsius3\Form\Type\UserTransformType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

final class TransformUserViewController extends BaseUserController
{
    public function __invoke($id, Request $request)
    {
        $entity = $this->findQuery('BaseUser', $id);

        if ($request->getMethod() === 'POST') {
            return $this->baseDoTransform(
                $id,
                UserTransformType::class,
                [
                    'instance' => $this->getInstance(),
                    'user' => $entity,
                    'user_actual' => $this->getUser()
                ],
                'admin_user'
            );
        }

        $response = $this->baseTransform(
            $id,
            UserTransformType::class,
            [
                'instance' => $this->getInstance(),
                'user' => $entity,
                'user_actual' => $this->getUser()
            ]
        );

        if ($response instanceof RedirectResponse) {
            return $response;
        }

        return $this->render('Admin/BaseUser/transform.html.twig', $response);
    }

    protected function getUserListRoute()
    {
        return 'admin_user';
    }
}
