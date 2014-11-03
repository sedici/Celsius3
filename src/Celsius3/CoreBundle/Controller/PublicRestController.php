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

        $counts = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Analytics\\UserAnalytics')->getYearUsersCounts();

        $data['total_users'][] = 'Total Users';
        $data['active_users'][] = 'Active Users';
        foreach ($counts as $count) {
            $data['categories'][] = $count->getYear();
            $data['total_users'][] = $count->getYearTotalUsers();
            $data['active_users'][] = $count->getYearActiveUsers();
        }

        $view = $this->view($data, 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/years_interval", name="public_rest_get_users_count_data_for_interval", options={"expose"=true})
     */
    public function getUsersCountDataForInterval(Request $request)
    {
        //Falta filtrar por instancia
        $initialYear = $request->query->get('initialYear');
        $finalYear = $request->query->get('finalYear');

        $interval = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:Analytics\\UserAnalytics')
                ->getUsersCountDataForYearsInterval($initialYear, $finalYear);

        $data['total_users'][] = 'Total Users';
        $data['active_users'][] = 'Active Users';
        $data['new_users'][] = 'New Users';
        foreach ($interval as $count) {
            $newUsers = 0;
            foreach ($count->getCounters() as $month) {
                $newUsers += (isset($month['newUsers'])) ? $month['newUsers'] : 0;
            }
            $data['categories'][] = $count->getYear();
            $data['total_users'][] = $count->getYearTotalUsers();
            $data['active_users'][] = $count->getYearActiveUsers();
            $data['new_users'][] = $newUsers;
        }

        $view = $this->view($data, 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/year", name="public_rest_get_users_count_data_for_year", options={"expose"=true})
     */
    public function getUsersCountDataForYear(Request $request)
    {
        //Falta filtrar por instancia
        $year = $request->query->get('year');

        $yearCounts = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:Analytics\\UserAnalytics')
                ->getUsersCountDataForYear($year);

        $counts = $yearCounts->getCounters();

        $data['total_users'][] = 'Total Users';
        $data['active_users'][] = 'Active Users';
        $data['new_users'][] = 'New Users';
        foreach ($counts as $count) {
            $data['categories'][] = \DateTime::createFromFormat('!m', $count['month'])->format('F');
            $data['total_users'][] = (isset($count['totalUsers'])) ? $count['totalUsers'] : 0;
            $data['active_users'][] = (isset($count['activeUsers'])) ? $count['activeUsers'] : 0;
            $data['new_users'][] = (isset($count['newUsers'])) ? $count['newUsers'] : 0;
        }

        $view = $this->view($data, 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/years", name="public_rest_get_years_data", options={"expose"=true})
     */
    public function getYearsData()
    {
        $years = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Analytics\\UserAnalytics')->getYears();

        foreach ($years as $year) {
            $data[] = $year->getYear();
        }

        $view = $this->view($data, 200)->setFormat('json');

        return $this->handleView($view);
    }

}
