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

namespace Celsius3\Controller\Api;

use Celsius3\Entity\Instance;
use FOS\RestBundle\Controller\FOSRestController;
use Celsius3\Entity\AccessToken;
use Celsius3\Exception\Exception;

class BaseController extends FOSRestController
{

    protected function getInstance()
    {
        $instance = $this->getDoctrine()->getManager()
                ->getRepository(Instance::class)
                ->find($this->get('request_stack')->getCurrentRequest()->request->get('instance_id'));

        if (!$instance) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.instance');
        }

        return $instance;
    }

    protected function validateAccessToken(AccessToken $token)
    {

        if (is_null($token)) {
            return false;
        }

        $actualDateTime = new \DateTime();
        $accessTokenExpires = (new \DateTime())->setTimestamp($token->getExpiresAt());

        if ($actualDateTime > $accessTokenExpires) {
            return false;
        }

        return true;
    }

    protected function getAccessTokenByToken($token)
    {
        $tokenManager = $this->get('fos_oauth_server.access_token_manager.default');
        return $tokenManager->findTokenByToken($token);
    }

}
