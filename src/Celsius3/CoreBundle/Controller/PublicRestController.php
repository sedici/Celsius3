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
     * @Get("/users_count", name="public_rest_get_users_count_data_for", options={"expose"=true})
     */
    public function getUsersCountDataFor(Request $request)
    {
        $instance = $request->query->get('instance');
        $type = $request->query->get('type');
        $initialYear = $request->query->get('initialYear');
        $finalYear = $request->query->get('finalYear');

        $newUsers = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:BaseUser')->countNewUsersFor($instance, $initialYear, $finalYear);
        $activeUsers = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Request')->countActiveUsersFor($instance, $type, $initialYear, $finalYear);

        $result = array();
        foreach ($newUsers as $count) {
            $result[$count['axisValue']]['newUsers'] = $count['newUsers'];
            $newUsers = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:BaseUser')->getTotalUsersUntilYear($instance,$count['axisValue']);
            $result[$count['axisValue']]['totalUsers'] = $newUsers['newUsers'];
        }
        foreach ($activeUsers as $count) {
            $result[$count['axisValue']]['activeUsers'] = $count['activeUsers'];
        }

        ksort($result, SORT_NUMERIC);

        $values = array();
        $values['newUsers'][] = 'New Users';
        $values['activeUsers'][] = 'Active Users';
        $values['totalUsers'][] = 'Total Users';
        foreach ($result as $year => $count) {
            $values['categories'][] = $year;
            $values['newUsers'][] = (isset($count['newUsers'])) ? $count['newUsers'] : 0;
            $values['activeUsers'][] = (isset($count['activeUsers'])) ? $count['activeUsers'] : 0;
            $values['totalUsers'][] = (isset($count['totalUsers'])) ? $count['totalUsers'] : 0;
        }

        $view = $this->view($values, 200)->setFormat('json');
        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/users_count_years", name="public_rest_get_users_count_years_data", options={"expose"=true})
     */
    public function getUsersCountYearsData(Request $request)
    {
        $instance = $request->query->get('instance');

        $userYears = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:BaseUser')->getYears($instance);

        $requestYears = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Request')->getYears($instance);

        foreach ($userYears as $year) {
            $data[] = $year['year'];
        }
        foreach ($requestYears as $year) {
            $data[] = $year['year'];
        }

        $data = array_unique($data);
        sort($data);

        $view = $this->view($data, 200)->setFormat('json');
        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/requests_count_years", name="public_rest_get_requests_count_years_data", options={"expose"=true})
     */
    public function getRequestsCountYearsData(Request $request)
    {
        $instance = $request->query->get('instance');

        $years = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:State')->getYears($instance);

        foreach ($years as $year) {
            $data[] = $year['year'];
        }

        $data = array_unique($data);
        sort($data);

        $view = $this->view($data, 200)->setFormat('json');
        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/requests_count_years", name="public_rest_get_requests_destiny_distribution_years_data", options={"expose"=true})
     */
    public function getRequestsDestinyDistributionYearsData(Request $request)
    {
        $instance = $request->query->get('instance');

        $years = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:State')->getYears($instance);

        foreach ($years as $year) {
            $data[] = $year['year'];
        }

        $data = array_unique($data);
        sort($data);

        $view = $this->view($data, 200)->setFormat('json');
        return $this->handleView($view);
    }
    
    /**
     * GET Route annotation.
     * @Get("/requests_number_years", name="public_rest_get_requests_number_by_publication_year_years_data", options={"expose"=true})
     */
    public function getRequestsNumberByPublicationYearYearsData(Request $request)
    {
        $instance = $request->query->get('instance');

        $years = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:State')->getYears($instance);//Cambiar método

        foreach ($years as $year) {
            $data[] = $year['year'];
        }

        $data = array_unique($data);
        sort($data);

        $view = $this->view($data, 200)->setFormat('json');
        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/requests_origin", name="public_rest_get_requests_origin_data", options={"expose"=true})
     */
    public function getRequestsOriginCountData(Request $request)
    {
        $instance = $request->query->get('instance');
        $type = $request->query->get('type');
        $country = $request->query->get('country');
        $institution = $request->query->get('institution');


        $requestRepository = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Institution');

        $counts = $requestRepository->countRequestsOrigin($instance, $type, $country, $institution);

        uasort($counts, function($a, $b) {
            if ($a['requestsCount'] == $b['requestsCount']) {
                return 0;
            }
            return ($a['requestsCount'] > $b['requestsCount']) ? -1 : 1;
        });

        $data = array();
        $data['requestsCount'][] = 'Requests';
        $i = 0;
        while ($i < 10) {
            list(, $count) = each($counts);
            $data['requestsCount'][] = $count['requestsCount'];
            $data['countries'][] = (Integer) $count['institutionCountry'];
            $data['categories'][] = $count['name'];
            $data['ids'][] = (Integer) $count['id'];

            $i++;
        }

        $view = $this->view($data, 200)->setFormat('json');
        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/requests_count", name="public_rest_get_requests_count_data_for", options={"expose"=true})
     */
    public function getRequestsCountDataFor(Request $request)
    {
        $instance = $request->query->get('instance');
        $type = $request->query->get('type');
        $initialYear = $request->query->get('initialYear');
        $finalYear = $request->query->get('finalYear');

        $result = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:State')
                ->findRequestsStateCountFor($instance,$type,$initialYear, $finalYear);
        
        $rows = array();
        foreach ($result as $count) {
            $rows[$count['axisValue']][$count['stateType']]['requestCount'] = $count['requestsCount'];
            $rows[$count['axisValue']][$count['stateType']]['totalPages'] = intval($count['endPage']) - intval($count['startPage']);
        }
        
        $values = array();
        $values['created'][] = 'Created';
        $values['cancelled'][] = 'Cancelled';
        $values['delivered'][] = 'Delivered';
        $values['totalPages'][] = 'Total Pages';
        foreach ($rows as $key => $row) {
            $values['categories'][] = $key;
            $values['created'][] = (isset($row['created']))? $row['created']['requestCount'] : 0;
            $values['cancelled'][] = (isset($row['cancelled']))? $row['cancelled']['requestCount'] : 0;
            $values['delivered'][] = (isset($row['delivered']))? $row['delivered']['requestCount'] : 0;
            $values['totalPages'][] = (isset($row['delivered']))? $row['delivered']['totalPages'] : 0;
        }

        $view = $this->view($values, 200)->setFormat('json');
        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/requests_destiny_distribution", name="public_rest_get_requests_destiny_distribution_data_for", options={"expose"=true})
     */
    public function getRequestsDestinyDistributionDataFor(Request $request)
    {
        $instance = $request->query->get('instance');
        $initialYear = $request->query->get('initialYear');
        $finalYear = $request->query->get('finalYear');
        $type = $request->query->get('type');

        $result = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:State')
                ->findRequestsDestinyDistributionFor($instance, $type, $initialYear, $finalYear);

        $values = array();
        foreach ($result as $count) {
            $values[$count['countryName']][$count['stateType']][] = $count['requestsCount'];
        }

        ksort($values);

        $data = array();
        $data['created'][] = 'Created';
        $data['cancelled'][] = 'Cancelled';
        $data['delivered'][] = 'Delivered';
        foreach ($values as $key => $val) {
            $data['categories'][] = $key;
            $data['created'][] = (isset($val['created'])) ? $val['created'] : 0;
            $data['cancelled'][] = (isset($val['cancelled'])) ? $val['cancelled'] : 0;
            $data['delivered'][] = (isset($val['delivered'])) ? $val['delivered'] : 0;
        }
        
        $view = $this->view($data, 200)->setFormat('json');
        return $this->handleView($view);
    }
    
    /**
     * GET Route annotation.
     * @Get("/requests_number_by_publication_year", name="public_rest_get_requests_number_by_publication_year_data_for", options={"expose"=true})
     */
    public function getRequestsNumberByPublicationYearDataFor(Request $request)
    {
        $instance = $request->query->get('instance');
        $initialYear = $request->query->get('initialYear');
        $finalYear = $request->query->get('finalYear');
        $type = $request->query->get('type');
        
        $result = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:Request')
                ->findRequestsNumberByPublicationYearFor($instance, $type, $initialYear, $finalYear);
        
        $data = array();
        $data['counts'][] = 'Cantidad';
        foreach ($result as $row) {
            $data['categories'][] = $row['materialDataYear'];
            $data['counts'][] = $row['materialDataCount'];
        }
        $div = count($data['categories']) * 0.1;
        for ($i=1; $i <= (count($data['categories']) / $div ); $i++){
            $data['tickValue'][] = $i * $div;
        }
        
        $view = $this->view($data, 200)->setFormat('json');
        return $this->handleView($view);
    }
}
