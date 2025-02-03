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

namespace Celsius3\Controller\Base;

use Celsius3\Controller\Core\EntityController;
use Celsius3\Controller\Core\HtmlRenderer;
use Celsius3\Controller\Core\RestRenderer;
use Celsius3\Entity\Instance;
use Celsius3\Entity\Country;

abstract class CountryController
{

    protected EntityController $controller;
    protected HtmlRenderer $htmlRenderer;
    protected RestRenderer $restRenderer;


    public function __construct(
        EntityController $controller,
        HtmlRenderer $htmlRenderer,
        RestRenderer $restRenderer
    ) {
        $this->controller = $controller;
        $this->htmlRenderer = $htmlRenderer;
        $this->restRenderer = $restRenderer;

        $this->initialize();
    }


    public function initialize(): void
    {
        $this->controller->setEntity(Country::class);
        $this->controller->setSortDefaults([
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        ]);
        $this->htmlRenderer->setTemplatePrefixFromObj($this);
    }


    protected function getDirectory(): Instance|null
    {
        return  $this->managerRegistry
            ->getRepository(Instance::class)
            ->findOneBy([
                'url' => 'directory'
            ]);
    }
}
