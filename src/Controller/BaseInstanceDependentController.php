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
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseInstanceDependentController extends BaseController
{
    /**
     * @var InstanceHelper
     */
    protected $instanceHelper;

    protected Instance $instance;

    protected string $filterType;

    public function __construct(
        InstanceHelper $instanceHelper,
        ...$args
    ) {
        parent::__construct(...$args);
        $this->instanceHelper = $instanceHelper;
        $this->instance = $this->getInstance();
        $this->filterType = $this->getFilterType();
    }

    protected function getFilterType(): string {return '';}

    protected function createFilterForm():\Symfony\Component\Form\FormInterface {
        return $this->createForm(
            $this->filterType,
            null,
            [
                'instance' => $this->instance,
            ]
        );
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

    protected function filterForm($query)
    {
        return $this->baseInstanceFilter(
            $this->entityClassName,
            $this->createFilterForm(),
            $query
        );
    }


    protected function baseInstanceUpdate(
        int $id,
        string $route,
        string $type = null,
        array $options = [],
        string $template = null
    ): RedirectResponse|Response {
        return $this->baseUpdate(
            $id,
            $route,
            $type,
            [
                'instance' => $this->instance,
                ... $options
            ],
            $template
        );
    }


    protected function baseInstanceEdit(
        int $id,
        string $type = null,
        string $route = null,
        array $options = [],
        string $template = null
    ): Response {
        return $this->baseEdit(
            $id,
            $type,
            [
                'instance' => $this->instance,
                ... $options
            ],
            $route,
            $template
        );
    }


    protected function baseInstanceCreate(
        Entity $entity = null,
        string $type = null,
        array $options = [],
        string $route = null,
        string $template = null
    ): RedirectResponse|Response {
        return $this->baseCreate(
            $entity,
            $type,
            [
                'instance' => $this->instance,
                ... $options
            ],
            $route,
            $template
        );
    }


    protected function baseInstanceNew(
        Entity $entity = null,
        string $type = null,
        array $options = [],
        string $template = null
    ): Response {
        return $this->baseNew(
            $entity,
            $type,
            [
                'instance' => $this->instance,
                ... $options
            ],
            $template
        );
    }


    protected function baseInstanceIndex(
        FormInterface $filter_form = null,
        array $options = [],
        string $type = null,
        string $template = null,
        $data = null
    ) {
        return $this->baseIndex(
            $filter_form,
            [
                'instance' => $this->getInstance(),
                ... $options
            ],
            $type,
            $template,
            $data
        );
    }
}
