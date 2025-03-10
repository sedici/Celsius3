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

namespace Celsius3\Controller;

use Celsius3\Controller\Base\CityController;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/rest/v1/admin/city")
 */
class RestAdminCityController extends CityController
{

    /**
     * @Get("/", name="rest_admin_city", options={"expose"=true})
     */
    public function restIndex(): Response
    { return $this->restRenderer->index('administration_order_show'); }


    /**
     * @Get("/{id}", name="rest_admin_city_show", options={"expose"=true})
     */
    public function restShow(string $id): Response
    { return $this->restRenderer->show($id, 'administration'); }
}
