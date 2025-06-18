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
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class InstanceHelper
{

    public const INSTANCE__DIRECTORY = 'directory';

    protected EntityRepository $repository;
    protected Instance $instance;
    protected Instance $directory;
    protected string $instanceUrl;
    protected string $instanceHost;

    public function __construct(
        protected RequestStack $requestStack,
        protected SessionInterface $session,
        protected EntityManagerInterface $entityManager
    ) {
        $this->repository = $this->entityManager->getRepository(Instance::class);
        $this->getSessionOrUrlInstance();
        $this->getDirectory();
    }


    public function getDirectory(): ?Instance
    {
        return $this->directory ??= $this->repository
            ->findOneBy([ 'url' => self::INSTANCE__DIRECTORY ]);
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
            throw Exception::create(Exception::INSTANCE_NOT_FOUND);
        }

        return $instance;
    }


    public function getSessionOrUrlInstance(): Instance
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($this->session->has('instance_url')) {
            $instanceUrl = $this->session->get('instance_url');

            if (!isset($this->instanceUrl) || $instanceUrl !== $this->instanceUrl) {
                $this->instanceUrl = $instanceUrl;

                $this->instance = $this->repository->findOneBy(
                    ['url' => $this->instanceUrl]
                );
            }
        }

        else if (!isset($this->instanceHost) || $request->getHost() !== $this->instanceHost) {
            $instanceHost = $request->getHost();

            if (!isset($this->instanceHost) || $instanceHost !== $this->instanceHost) {
                $this->instanceHost = $instanceHost;

                $this->instance = $this->repository->findOneBy(
                    ['host' => $this->instanceHost]
                );
            }
        }

        return $this->instance;
    }


    public function findInstance(
        float $latitude,
        float $longitude,
        int $limit = 10
    ): array {
        $temRes = $this->repository
            ->findInstancesOrderedByDistance(
                $latitude,
                $longitude,
                $limit
            );

        $res = [];
        foreach ($temRes as $tem) {
            $res[] = $tem[0];
        }

        return $res;
    }
}
