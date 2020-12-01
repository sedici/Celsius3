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

namespace Celsius3\CoreBundle\Controller\Admin\BaseUser;

use Celsius3\CoreBundle\Controller\BaseUserController;
use FOS\UserBundle\Model\UserManagerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

final class SwitchUserPutController extends BaseUserController
{
    private $userManager;
    private $tokenStorage;

    /**
     * @DI\InjectParams({
     *     "userManager" = @DI\Inject("fos_user.user_manager"),
     *     "tokenStorage" = @DI\Inject("security.token_storage"),
     * })
     */
    public function __construct(UserManagerInterface $userManager, TokenStorage $tokenStorage)
    {
        $this->userManager = $userManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function __invoke(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->userManager->findUserByUsername($request->get('_switch_user'));
            $token = new UsernamePasswordToken($user, $user->getPassword(), 'secured_area', $user->getRoles());
            $this->tokenStorage->setToken($token);
        }

        return $this->redirectToRoute('user_index', []);
    }
}
