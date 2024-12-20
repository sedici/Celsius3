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

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormInterface;

abstract class BaseInstanceDependentController extends BaseEntityController
{

    protected function listQuery(): QueryBuilder
    {
        return $this->repository
            ->findForInstanceAndGlobal(
                $this->instance,
                $this->directory
            );
    }


    protected function findQuery(string $id)
    {
        return $this->repository
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


    protected function baseInstanceUpdate(
        string $id,
        string $route,
        string $type = null,
        array $options = [],
        string $template = null,
        Entity $entity = null
    ): RedirectResponse|Response {
        return $this->baseUpdate(
            $id, $route, $type,
            [
                'instance' => $this->instance, ... $options
            ], $template,
            $entity
        );
    }


    protected function baseInstanceEdit(
        string $id,
        string $type = null,
        string $route = null,
        array $options = [],
        string $template = null,
        Entity $entity = null,
    ): Response {
        return $this->baseEdit(
            $id, $type,
            [
                'instance' => $this->instance, ... $options
            ], $route, $template,
            $entity
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
            $entity, $type,
            [
                'instance' => $this->instance, ... $options
            ], $route, $template
        );
    }


    protected function baseInstanceNew(
        Entity $entity = null,
        string $type = null,
        array $options = [],
        string $template = null
    ): Response {
        return $this->baseNew(
            $entity, $type,
            [
                'instance' => $this->instance, ... $options
            ], $template
        );
    }


    protected function baseInstanceIndex(
        string $type = null,
        array $options = [],
        string $template = null,
        $data = null,
        FormInterface $filter_form = null,
        bool $hasFilterForm = true
    ): Response {
        return $this->baseIndex(
            $type,
            [
                'instance' => $this->instance, ... $options
            ], $template, $data,
            $filter_form, $hasFilterForm
        );
    }


    protected function createForm(
        string $type = null,
        $data = null,
        array $formOptions = [],
        bool $hasData = true
    ): FormInterface {
        if (empty($formOptions)) $formOptions = [ 'instance' => $this->instance ];
        else if (! array_key_exists('instance', $formOptions))
            $formOptions = [ 'instance' => $this->instance, ...$formOptions ];

        return parent::createForm(
            $type, $data, $formOptions, $hasData
        );
    }
}
