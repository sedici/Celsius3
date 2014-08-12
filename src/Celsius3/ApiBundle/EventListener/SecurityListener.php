<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

namespace Celsius3\ApiBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Doctrine\ODM\MongoDB\DocumentManager;

class SecurityListener
{
    private $dm;
    private $cacheDir;

    public function __construct(DocumentManager $dm, $cacheDir)
    {
        $this->dm = $dm;
        $this->cacheDir = $cacheDir;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) {
            // don't do anything if it's not the master request
            return;
        }

        $request = $event->getRequest();

        if (1 !== preg_match('/ApiBundle/', $request->attributes->get('_controller'))) {
            // don't do anything if it's not an api request
            return;
        }

        $wsseRegex = '/UsernameToken Username="([^"]+)", PasswordDigest="([^"]+)", Nonce="([^"]+)", Created="([^"]+)"/';
        if (!$request->headers->has('x-wsse') || 1 !== preg_match($wsseRegex, $request->headers->get('x-wsse'), $matches)) {
            return $this->generate403Response($event);
        }

        $instance = $this->dm->getRepository('Celsius3CoreBundle:Instance')
                ->findOneBy(array('url' => $matches[1]));

        try {
            if ($instance && $instance->getEnabled() && $this->validateDigest($matches[2], $matches[3], $matches[4], $instance->get('api_key')->getValue())) {
                $request->request->set('instance_id', $instance->getId());
                // everything cool
                return;
            }

            throw new AuthenticationException('The WSSE authentication failed.');
        } catch (AuthenticationException $failed) {
            // ... you might log something here
            // To deny the authentication clear the token. This will redirect to the login page.
            // Make sure to only clear your token, not those of other authentication listeners.
            // $token = $this->securityContext->getToken();
            // if ($token instanceof WsseUserToken && $this->providerKey === $token->getProviderKey()) {
            //     $this->securityContext->setToken(null);
            // }
            // return;
            // Deny authentication with a '403 Forbidden' HTTP response
            return $this->generate403Response($event);
        }

        return $this->generate403Response($event);
    }

    private function validateDigest($digest, $nonce, $created, $secret)
    {
        // Check created time is not in the future
        if (strtotime($created) > time()) {
            return false;
        }

        // Expire timestamp after 5 minutes
        if (time() - strtotime($created) > 300) {
            return false;
        }

        // Validate Secret
        $expected = base64_encode(sha1(base64_decode($nonce) . $created . $secret, true));

        return $digest === $expected;
    }

    private function generate403Response(GetResponseEvent $event)
    {
        $response = new Response();
        $response->setStatusCode(403);
        $event->setResponse($response);
    }
}