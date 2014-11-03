<?php
/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

namespace Celsius3\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;

/**
 * User controller.
 *
 * @Route("/admin/rest/city")
 */
class AdminCityRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("/{country_id}", name="admin_rest_city", options={"expose"=true})
     */
    public function getCitiesAction($country_id)
    {
        $em = $this->getDoctrine()->getManager();

        $countries = $em->getRepository('Celsius3CoreBundle:City')
                ->findForInstanceAndGlobal($this->getInstance(), $this->getDirectory(), $country_id);

        $view = $this->view(array_values($countries), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{id}", name="admin_rest_city_get", options={"expose"=true})
     */
    public function getCityAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $institution = $em->getRepository('Celsius3CoreBundle:City')
                ->find($id);

        if (!$institution) {
            return $this->createNotFoundException('City not found.');
        }

        $view = $this->view($institution, 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

}