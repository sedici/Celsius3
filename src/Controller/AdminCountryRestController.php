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

use Celsius3\CoreBundle\Entity\Country;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use JMS\Serializer\SerializationContext;
use Celsius3\Exception\Exception;

/**
 * User controller.
 *
 * @Route("/admin/rest/country")
 */
class AdminCountryRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("", name="admin_rest_country", options={"expose"=true})
     */
    public function getCountries()
    {
        $context = SerializationContext::create()->setGroups(array('administration_order_show'));

        $em = $this->getDoctrine()->getManager();

        $countries = $em->getRepository(Country::class)
                ->findForInstanceAndGlobal($this->getInstance(), $this->getDirectory())
                ->getQuery()
                ->execute();

        $view = $this->view(array_values($countries), 200)->setFormat('json');
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{id}", name="admin_rest_country_get", options={"expose"=true})
     */
    public function getCountry($id)
    {
        $em = $this->getDoctrine()->getManager();

        $country = $em->getRepository(Country::class)->find($id);

        if (!$country) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.country');
        }

        $view = $this->view($country, 200)->setFormat('json');

        return $this->handleView($view);
    }

}
