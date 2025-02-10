<?php

/*
 * Celsius3 - Instance dependent controller
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

namespace Celsius3\Controller\Core;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;

class InstanceDependentController extends Controller
{

    protected bool $isInstanceDependent = true;


    protected function setInstanceDependent(bool $isInstanceDependent): void
    { $this->isInstanceDependent = $isInstanceDependent; }


    public function listQuery(
        ?bool $isInstanceDependent = null
    ): QueryBuilder {
        if (!$isInstanceDependent && !$this->isInstanceDependent)
            return parent::listQuery();

        return $this->repository
            ->findForInstanceAndGlobal(
                $this->instance,
                $this->directory
            );
    }


    public function findQuery(
        string $id,
        ?bool $isInstanceDependent = null
    ): mixed {
        if (!$isInstanceDependent && !$this->isInstanceDependent)
            return parent::findQuery($id);

        return $this->repository
            ->findOneForInstance(
                $this->instance,
                $id
            );
    }


    public function getResultsPerPage(
        ?bool $isInstanceDependent = null
    ): bool|int|string|null {
        if (!$isInstanceDependent && !$this->isInstanceDependent)
            return parent::getResultsPerPage();

        return $this->configurationHelper
            ->getCastedValue(
                $this->instance
                    ->get('results_per_page')
            );
    }


    public function createForm(
        string $type,
        $data = null,
        array $options = [],
        ?bool $isInstanceDependent = null
    ): FormInterface {
        if (!$isInstanceDependent && !$this->isInstanceDependent)
            return parent::createForm(
                $type, $data, $options
            );

        if (empty($options)) $options = [ 'instance' => $this->instance ];
        else if (! array_key_exists('instance', $options))
            $options = [ 'instance' => $this->instance , ...$options ];

        return parent::createForm(
            $type, $data, $options
        );
    }
}