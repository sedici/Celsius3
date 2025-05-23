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

use Celsius3\Entity\Hive;
use Celsius3\Entity\Instance;
use Celsius3\Exception\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class InstanceHelper
{

    protected EntityRepository $repository;

    public function __construct(
        protected RequestStack $requestStack,
        protected SessionInterface $session,
        protected EntityManagerInterface $entityManager
    ) {
        $this->repository = $this->entityManager->getRepository(Instance::class);
    }

    public function getSessionInstance()
    {
        $instance = $this->repository
            ->find($this->session->get('instance_id'));

        if (!$instance) {
            throw Exception::create(Exception::INSTANCE_NOT_FOUND);
        }

        return $instance;
    }

    public function getUrlInstance()
    {
        $request = $this->requestStack->getCurrentRequest();
        $instance = $this->repository
            ->findOneBy(['host' => $request->getHost()]);

        if (!$instance) {
            throw Exception::create(Exception::INSTANCE_NOT_FOUND, 'exception.not_found.instance');
        }

        return $instance;
    }

    public function getSessionOrUrlInstance()
    {
        $request = $this->requestStack->getCurrentRequest();

        $instance = $this->session->has('instance_url')
            ? $this->repository->findOneBy(
                ['url' => $this->session->get('instance_url')]
            )
            : $this->repository->findOneBy(
                ['host' => ($request !== null) ? $request->getHost() : '']
            );
        
        // $instance = $this->repository->findOneBy(
        //     ['url' => $this->session->get('instance_url')]
        // );

        // if (!$instance) echo 'no instance url: ' . (string)var_dump($this->session->get('instance_url'));

        return $instance;
    }
}
