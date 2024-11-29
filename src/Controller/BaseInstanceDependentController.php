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

namespace Celsius3\Controller;

use Celsius3\Entity\Instance;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\FilterManager;

abstract class BaseInstanceDependentController extends BaseController
{
    /**
     * @var InstanceHelper
     */
    protected $instanceHelper;

    protected Instance $instance;

    public function __construct(
        InstanceHelper $instanceHelper,
        ...$parentArgs
    ) {
        parent::__construct(...$parentArgs);
        $this->instanceHelper = $instanceHelper;
        $this->instance = $this->getInstance();
    }


    protected function getInstance(): Instance
    {
        return $this->instanceHelper->getSessionInstance();
    }


    protected function listQuery()
    {
        return $this->managerRegistry->getManager()
            ->getRepository(className: $this->entityClassName)
            ->findForInstanceAndGlobal(
                $this->instance,
                $this->getDirectory()
            );
    }


    protected function findQuery(int $id)
    {
        return $this->managerRegistry->getManager()
            ->getRepository($this->entityClassName)
            ->findOneForInstance(
                $this->instance,
                $id
            );
    }


    protected function getResultsPerPage()
    {
        return $this->configurationHelper
            ->getCastedValue(
                $this->instance
                    ->get('results_per_page')
            );
    }


    protected function baseInstanceFilter($entityClassName, $filter_form, $query)
    {
        return $this->objectManager
            ->getRepository(FilterManager::class)
            ->filter(
                $query,
                $filter_form,
                $entityClassName,
                $this->instance
            );
    }

    protected function filter($query)
    {
        return $this->baseInstanceFilter(
            $this->entityClassName,
            $this->getFilterForm(),
            $query
        );
    }
}
