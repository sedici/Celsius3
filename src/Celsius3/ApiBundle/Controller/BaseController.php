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

namespace Celsius3\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Celsius3\ApiBundle\Entity\AccessToken;

class BaseController extends FOSRestController
{

    protected function getInstance()
    {
        $instance = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:Instance')
                ->find($this->get('request_stack')->getCurrentRequest()->request->get('instance_id'));

        if (!$instance) {
            return $this->createNotFoundException('Instance not found');
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
