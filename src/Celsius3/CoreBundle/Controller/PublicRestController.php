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

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use JMS\Serializer\SerializationContext;

/**
 * User controller.
 *
 * @Route("/public/rest")
 */
class PublicRestController extends BaseInstanceDependentRestController
{
     /**
     * GET Route annotation.
     * @Get("/", name="public_rest_get_users_count_data", options={"expose"=true})
     */
    public function getUsersCountData()
    {
        
        $counts = $this->getDocumentManager()->getRepository('Celsius3CoreBundle:Analytics\\UserAnalytics')->getYearUsersCounts();
        
        $data['total_users'][] = 'Total Users';
        $data['active_users'][] = 'Active Users';
        foreach ($counts as $count){
            $data['categories'][] = $count->getYear();
            $data['total_users'][] = $count->getYearTotalUsers();
            $data['active_users'][] = $count->getYearActiveUsers();
        }
        
        $view = $this->view($data, 200)->setFormat('json');

        return $this->handleView($view);
    }

}
