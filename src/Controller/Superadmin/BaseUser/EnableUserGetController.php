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

namespace Celsius3\Controller\Superadmin\BaseUser;

use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\Exception\Exception;
use Celsius3\Repository\BaseUserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnableUserGetController extends AbstractController
{
    private $baseUserRepository;

    public function __construct(BaseUserRepositoryInterface $baseUserRepository)
    {
        $this->baseUserRepository = $baseUserRepository;
    }

    public function __invoke(Request $request, $id): Response
    {
        $user = $this->baseUserRepository->find($id);

        if (!$user) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.user');
        }

        $this->enableUser($user);

        return $this->redirect($request->headers->get('referer'));
    }

    private function enableUser(BaseUser $user): void
    {
        $user->setEnabled(true);
        $this->baseUserRepository->save($user);
    }
}
