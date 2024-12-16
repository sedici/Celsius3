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

namespace Celsius3\Controller\SuperAdmin\BaseUser;

use Celsius3\Controller\BaseEntityController;
use Celsius3\Entity\BaseUser;
use Celsius3\Form\Type\BaseUserType;
use Celsius3\Form\Type\Filter\BaseUserFilterType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ListAllUsersViewController extends BaseEntityController
{

    protected final function getEntity(): string
    { return BaseUser::class; }

    protected final function getType(): string
    { return BaseUserType::class; }

    protected final function getTemplatePrefix(): string
    { return 'Superadmin/BaseUser/'; }


    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.surname',
            'defaultSortDirection' => 'asc',
        ];
    }

    public function __invoke(Request $request): Response
    {
        return $this->baseIndex(
            filter_form: $this->createForm(BaseUserFilterType::class)
        );
    }
}
