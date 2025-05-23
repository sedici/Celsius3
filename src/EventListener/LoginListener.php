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

namespace Celsius3\EventListener;

use Celsius3\Entity\BaseUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use function PHPUnit\Framework\isInstanceOf;

#[AsEventListener(
    event: AuthenticationSuccessEvent::class,
    method: 'onSecurityInteractiveLogin'
)]
class LoginListener
{

    protected $userRepository;

    public function __construct(
        protected SessionInterface $session,
        protected EntityManagerInterface $entityManager
    ) {
        $this->userRepository = $this->entityManager
            ->getRepository(BaseUser::class);
    }

    public function onSecurityInteractiveLogin(AuthenticationSuccessEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();
        $user = ($user instanceof BaseUser)
            ? $this->userRepository->find($user->getId())
            : null;
        if (!$user) throw new \Exception('User not found');
        dump($user);
        dump('si paso por acá');
        if ($user instanceof UserInterface) {
            $this->session->set('instance_id', $user->getInstance()->getId());
            $this->session->set('instance_url', $user->getInstance()->getUrl());
            $this->session->set('instance_host', $user->getInstance()->getHost());
        }
    }
}
