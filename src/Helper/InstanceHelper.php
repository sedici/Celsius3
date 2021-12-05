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

namespace Celsius3\Helper;

use Celsius3\Entity\Instance;
use Celsius3\Exception\Exception;
use Celsius3\Repository\InstanceRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class InstanceHelper
{
    private $requestStack;
    private $session;
    /**
     * @var InstanceRepositoryInterface
     */
    private $instanceRepository;

    public function __construct(
        RequestStack $requestStack,
        SessionInterface $session,
        InstanceRepositoryInterface $instanceRepository
    ) {
        $this->requestStack = $requestStack;
        $this->session = $session;
        $this->instanceRepository = $instanceRepository;
    }

    public function getSessionInstance()
    {
        $instance = $this->instanceRepository
            ->find($this->session->get('instance_id'));

        if (!$instance) {
            throw Exception::create(Exception::INSTANCE_NOT_FOUND);
        }

        return $instance;
    }

    public function getUrlInstance()
    {
        $request = $this->requestStack->getCurrentRequest();
        $instance = $this->instanceRepository
            ->findOneBy(['host' => $request->getHost()]);

        if (!$instance) {
            throw Exception::create(Exception::INSTANCE_NOT_FOUND, 'exception.not_found.instance');
        }

        return $instance;
    }

    public function getSessionOrUrlInstance()
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($this->session->has('instance_url')) {
            $instance = $this->instanceRepository
                ->findOneBy(['url' => $this->session->get('instance_url')]);
        } else {
            $instance = $this->instanceRepository
                ->findOneBy(['host' => ($request !== null) ? $request->getHost() : '']);
        }

        return $instance;
    }
}
