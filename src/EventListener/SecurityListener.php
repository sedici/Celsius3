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

use Celsius3\Entity\Instance;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use FOS\OAuthServerBundle\Entity\AccessTokenManager;
use Celsius3\Entity\BaseUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class SecurityListener
{
    private $entityManager;
    private $tokenManager;
    private $tokenStorage;

    public function __construct(EntityManager $entityManager, AccessTokenManager $tokenManager, TokenStorage $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->tokenManager = $tokenManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $instance = $this->entityManager->getRepository(Instance::class)
                ->findOneBy(array('host' => $request->getHost()));
        $request->request->set('instance_id', $instance);

        $uri = $request->getUri();

        $user = (!is_null($this->tokenStorage->getToken())) ? $this->tokenStorage->getToken()->getUser() : null;

        if ((false !== strpos($uri, '/oauth/v2/auth')) && ($user instanceof BaseUser)) {
            if (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_SUPERADMIN', $user->getRoles())) {
                return;
            }

            $this->generateErrorResponse($event, 401);

            return;
        }

        if ((false !== strpos($uri, '/api')) && !(false !== strpos($uri, '/oauth/v2/auth') || false !== strpos($uri, '/oauth/v2/token') || false !== strpos($uri, '/users/current_user') || false !== strpos($uri, '/received_at_update') || false !== strpos($uri, '/_trans'))) {
            $access_token = $request->query->get('access_token');

            if (is_null($access_token)) {
                $this->generateErrorResponse($event, 403);

                return;
            }

            if (!$this->validateToken($access_token)) {
                $event->setResponse((new JsonResponse())->setData(array('validAccessToken' => false)));

                return;
            }
        }
    }

    private function validateToken($accessToken)
    {
        $token = $this->tokenManager->findTokenByToken($accessToken);

        if (!is_null($token)) {
            $tokenExpires = (new \DateTime())->setTimestamp($token->getExpiresAt());
            $actualDateTime = new \DateTime();

            return $actualDateTime < $tokenExpires;
        }

        return false;
    }

    private function generateErrorResponse(GetResponseEvent $event, $statusCode)
    {
        $response = new Response();
        $response->setStatusCode($statusCode);
        $event->setResponse($response);
    }
}
