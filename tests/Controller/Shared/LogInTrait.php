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

namespace Celsius3\Tests\Controller\Shared;

use Celsius3\CoreBundle\Entity\BaseUser;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait LogInTrait
{
    private function logInUser(Client $client): BaseUser
    {
        return $this->logIn($client, getenv('TEST_USER_USERNAME'));
    }

    private function logInAdmin(Client $client): BaseUser
    {
        return $this->logIn($client, getenv('TEST_ADMIN_USERNAME'));
    }

    private function logIn(Client $client, string $username): BaseUser
    {
        $session = $client->getContainer()->get('session');

        $firewall_name = 'main';
        $firewall_context = 'main';

        $entity_manager = $client->getContainer()->get('doctrine.orm.default_entity_manager');
        $user = $entity_manager->getRepository(BaseUser::class)
            ->findOneBy(['username' => $username]);

        $token = new UsernamePasswordToken($user, null, $firewall_name, $user->getRoles());
        $session->set('_security_' . $firewall_context, serialize($token));

        $session->set('instance_id', $user->getInstance()->getId());
        $session->set('instance_url', $user->getInstance()->getUrl());
        $session->set('instance_host', $user->getInstance()->getHost());

        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);

        return $user;
    }
}
