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
    public function getUsersCountDataForAction(Request $request)
    {
        $instance = $request->query->get('instance');
        $initialYear = $request->query->get('initialYear');
        $finalYear = $request->query->get('finalYear');
        $type = $request->query->get('type');


        $newUsers = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:BaseUser')->countNewUsersFor($instance, $initialYear, $finalYear);
        $activeUsers = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Request')->countActiveUsersFor($instance, $type, $initialYear, $finalYear);

        $result = array();
        foreach ($newUsers as $count) {
            $result[$count['axisValue']]['newUsers'] = $count['newUsers'];
            $newUsers = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:BaseUser')->getTotalUsersUntil($instance, $initialYear, $finalYear, $count['axisValue']);
            $result[$count['axisValue']]['totalUsers'] = $newUsers['newUsers'];
        }
        foreach ($activeUsers as $count) {
            $result[$count['axisValue']]['activeUsers'] = $count['activeUsers'];
        }

        ksort($result, SORT_NUMERIC);

        $values = array();
        $values['columns']['newUsers'][] = 'New Users';
        $values['columns']['activeUsers'][] = 'Active Users';
        $values['columns']['totalUsers'][] = 'Total Users';
        foreach ($result as $year => $count) {
            $values['categories'][] = $year;
            $values['columns']['newUsers'][] = (isset($count['newUsers'])) ? $count['newUsers'] : 0;
            $values['columns']['activeUsers'][] = (isset($count['activeUsers'])) ? $count['activeUsers'] : 0;
            $values['columns']['totalUsers'][] = (isset($count['totalUsers'])) ? $count['totalUsers'] : 0;
        }

        $view = $this->view($values, 200)->setFormat('json');
        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/requests_origin", name="public_rest_get_requests_origin_data", options={"expose"=true})
     */
    public function getRequestsOriginCountDataAction(Request $request)
    {
        $instance = $request->query->get('instance');
        $type = $request->query->get('type');
        $country = $request->query->get('country');
        $institution = $request->query->get('institution');
        $initialYear = $request->query->get('initialYear');
        $finalYear = $request->query->get('finalYear');

        $counts = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Institution')
                ->countRequestsOrigin($instance, $type, $initialYear, $finalYear, $country, $institution);

        uasort($counts, function($a, $b) {
            if ($a['requestsCount'] === $b['requestsCount']) {
                return 0;
            }
            return ($a['requestsCount'] > $b['requestsCount']) ? -1 : 1;
        });

        $data = array();
        $data['columns']['requestsCount'][] = 'Requests';
        $data['categories'] = Array();
        foreach ($counts as $count) {
            $data['columns']['requestsCount'][] = $count['requestsCount'];
            $data['countries'][] = (Integer) $count['institutionCountry'];
            $data['categories'][] = $count['name'];
            $data['ids'][] = (Integer) $count['id'];
        }

        $view = $this->view($data, 200)->setFormat('json');
        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/requests_count", name="public_rest_get_requests_count_data_for", options={"expose"=true})
     */
    public function getRequestsCountDataForAction(Request $request)
    {
        $instance = $request->query->get('instance');
        $type = $request->query->get('type');
        $initialYear = $request->query->get('initialYear');
        $finalYear = $request->query->get('finalYear');

        $result = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:State')
                ->findRequestsStateCountFor($instance, $type, $initialYear, $finalYear);

        $rows = array();
        foreach ($result as $count) {
            $rows[$count['axisValue']][$count['stateType']]['requestCount'] = $count['requestsCount'];
            $rows[$count['axisValue']][$count['stateType']]['totalPages'] = intval($count['pages']);
        }

        $values = array();
        $values['columns']['created'][] = 'Created';
        $values['columns']['cancelled'][] = 'Cancelled';
        $values['columns']['satisfied'][] = 'Satisfied';
        $values['totalPages'][] = 'Total Pages';
        foreach ($rows as $key => $row) {
            $values['categories'][] = $key;
            $values['columns']['created'][] = (isset($row['created'])) ? $row['created']['requestCount'] : 0;
            $values['columns']['cancelled'][] = (isset($row['cancelled'])) ? $row['cancelled']['requestCount'] : 0;
            $values['columns']['satisfied'][] = (isset($row['received'])) ? $row['received']['requestCount'] : 0;
            $values['totalPages'][] = (isset($row['received'])) ? $row['received']['totalPages'] : 0;
        }

        $view = $this->view($values, 200)->setFormat('json');
        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/requests_destiny_distribution", name="public_rest_get_requests_destiny_distribution_data_for", options={"expose"=true})
     */
    public function getRequestsDestinyDistributionDataForAction(Request $request)
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

        uasort($values, function($a, $b) {
            if (!array_key_exists('created', $a) && !array_key_exists('created', $b)) {
                return 0;
            }
            if (!array_key_exists('created', $a) && array_key_exists('created', $b)) {
                return 1;
            }
            if (array_key_exists('created', $a) && !array_key_exists('created', $b)) {
                return -1;
            }
            if ($a['created'] === $b['created']) {
                return 0;
            }
            return ($a['created'] > $b['created']) ? -1 : 1;
        });

        $data = array();
        $data['columns']['created'][] = 'Created';
        $data['columns']['cancelled'][] = 'Cancelled';
        $data['columns']['delivered'][] = 'Delivered';
        foreach ($values as $key => $val) {
            $data['categories'][] = $key;
            $data['columns']['created'][] = (isset($val['created'])) ? $val['created'][0] : 0;
            $data['columns']['cancelled'][] = (isset($val['cancelled'])) ? $val['cancelled'][0] : 0;
            $data['columns']['delivered'][] = (isset($val['delivered'])) ? $val['delivered'][0] : 0;
        }

        $view = $this->view($data, 200)->setFormat('json');
        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/requests_number_by_publication_year", name="public_rest_get_requests_number_by_publication_year_data_for", options={"expose"=true})
     */
    public function getRequestsNumberByPublicationYearDataForAction(Request $request)
    {
        $instance = $request->query->get('instance');
        $initialYear = $request->query->get('initialYear');
        $finalYear = $request->query->get('finalYear');
        $type = $request->query->get('type');

        $result = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:Request')
                ->findRequestsNumberByPublicationYearFor($instance, $type, $initialYear, $finalYear);

        $data = array();
        $data['columns']['counts'][] = 'Cantidad';
        foreach ($result as $row) {
            $data['categories'][] = $row['materialDataYear'];
            $data['columns']['counts'][] = $row['materialDataCount'];
        }
        $div = count($data['categories']) * 0.1;
        for ($i = 1; $i <= (count($data['categories']) / $div ); $i++) {
            $data['tickValue'][] = $i * $div;
        }

        $view = $this->view($data, 200)->setFormat('json');
        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/requests_total_delay", name="public_rest_get_requests_total_delay_data_for", options={"expose"=true})
     */
    public function getRequestsTotalDelayDataForAction(Request $request)
    {
        $instance = $request->query->get('instance');
        $initialYear = $request->query->get('initialYear');
        $finalYear = $request->query->get('finalYear');
        $type = $request->query->get('type');
        $delayType = $request->query->get('delayType');
        $result = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Request')->findRequestsDelay($instance, $type, $initialYear, $finalYear, $delayType);

        $order = array();
        foreach ($result as $row) {
            if ($row['rCount'] > 0) {
                if ($row['delay'] >= 9) {
                    $order[$row['cYear']][9] = isset($order[$row['cYear']][9]) ? $order[$row['cYear']][9] + $row['rCount'] : $row['rCount'];
                } elseif ($row['delay'] >= 0) {
                    $order[$row['cYear']][$row['delay']] = isset($order[$row['cYear']][$row['delay']]) ? $order[$row['cYear']][$row['delay']] + $row['rCount'] : $row['rCount'];
                }
            }
        }

        $data = array();
        $data['columns']['delay0'][] = 'Delay 0';
        $data['columns']['delay1'][] = 'Delay 1';
        $data['columns']['delay2'][] = 'Delay 2';
        $data['columns']['delay3'][] = 'Delay 3';
        $data['columns']['delay4'][] = 'Delay 4';
        $data['columns']['delay5'][] = 'Delay 5';
        $data['columns']['delay6'][] = 'Delay 6';
        $data['columns']['delay7'][] = 'Delay 7';
        $data['columns']['delay8'][] = 'Delay 8';
        $data['columns']['delay9'][] = 'Delay 9';
        foreach ($order as $k => $d) {
            $data['categories'][] = $k;
            $data['columns']['delay0'][] = isset($d[0]) ? $d[0] : 0;
            $data['columns']['delay1'][] = isset($d[1]) ? $d[1] : 0;
            $data['columns']['delay2'][] = isset($d[2]) ? $d[2] : 0;
            $data['columns']['delay3'][] = isset($d[3]) ? $d[3] : 0;
            $data['columns']['delay4'][] = isset($d[4]) ? $d[4] : 0;
            $data['columns']['delay5'][] = isset($d[5]) ? $d[5] : 0;
            $data['columns']['delay6'][] = isset($d[6]) ? $d[6] : 0;
            $data['columns']['delay7'][] = isset($d[7]) ? $d[7] : 0;
            $data['columns']['delay8'][] = isset($d[8]) ? $d[8] : 0;
            $data['columns']['delay9'][] = isset($d[9]) ? $d[9] : 0;
        }

        $view = $this->view($data, 200)->setFormat('json');
        return $this->handleView($view);
    }
}
