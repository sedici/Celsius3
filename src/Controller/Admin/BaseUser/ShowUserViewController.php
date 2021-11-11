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
use Celsius3\Exception\Exception;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;

final class ShowUserViewController extends BaseUserController
{
    private $threadManager;
    
    public function __construct(ThreadManagerInterface $threadManager)
    {
        $this->threadManager = $threadManager;
    }

    public function __invoke($id)
    {
        $user = $this->findUser($id);
        $messages = $this->findMessagesForUser($user);

        $parameters = [
            'element' => $user,
            'messages' => $messages,
            'resultsPerPage' => $this->getResultsPerPage(),
        ];

        return $this->render('Admin/BaseUser/show.html.twig', $parameters);
    }

    private function findUser($id)
    {
        $user = $this->findQuery('BaseUser', $id);

        if (!$user) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.user');
        }

        return $user;
    }

    private function findMessagesForUser($user)
    {
        $messages = $this->threadManager
            ->getParticipantSentThreadsQueryBuilder($user)
            ->getQuery()->getResult();

        return $messages;
    }
}
