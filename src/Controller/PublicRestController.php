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

use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Entity\Event\Event;
use Celsius3\CoreBundle\Entity\Institution;
use Celsius3\CoreBundle\Entity\State;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\ViewHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * User controller.
 *
 * @Route("/public/rest")
 */
class PublicRestController extends FOSRestController//BaseInstanceDependentRestController
{
    private $translator;
    private $entityManager;
    private $viewHandler;

    public function __construct(
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        ViewHandlerInterface $viewHandler
    )
{
    $this->translator = $translator;
    $this->entityManager = $entityManager;
    $this->viewHandler = $viewHandler;
}

    /**
     * GET Route annotation.
     * @Get("/users_count.{_format}", name="public_rest_get_users_count_data_for", options={"expose"=true}, defaults={"_format"="json"})
     */
    public function getUsersCountDataFor(Request $request)
    {
        $translator = $this->translator;

        $instance = $request->query->get('instance');
        $initialYear = $request->query->get('initialYear');
        $finalYear = $request->query->get('finalYear');
        $type = $request->query->get('type');


        $newUsers = $this->entityManager
                ->getRepository(BaseUser::class)
                ->countNewUsersFor($instance, $initialYear, $finalYear);
        $activeUsers = $this->entityManager
                ->getRepository(\Celsius3\CoreBundle\Entity\Request::class)
                ->countActiveUsersFor($instance, $type, $initialYear, $finalYear);

        $result = array();
        $total = 0;
        foreach ($newUsers as $count) {
            $result[$count['axisValue']]['newUsers'] = $count['newUsers'];
            $total = $result[$count['axisValue']]['totalUsers'] = $total + $count['newUsers'];
        }
        foreach ($activeUsers as $count) {
            $result[$count['axisValue']]['activeUsers'] = $count['activeUsers'];
        }

        ksort($result, SORT_NUMERIC);

        $values = array();

        $values['names']['newUsers'][] = $translator->trans('New Users');
        $values['names']['activeUsers'][] = $translator->trans('Active Users');
        $values['names']['totalUsers'][] = $translator->trans('Total Users');

        $values['columns']['newUsers'][] = 'newUsers';
        $values['columns']['activeUsers'][] = 'activeUsers';
        $values['columns']['totalUsers'][] = 'totalUsers';
        foreach ($result as $axis => $count) {
            $values['categories'][] = ($initialYear === $finalYear) ?
                $translator->trans(strftime('%B', mktime(0, 0, 0, $axis, 10)))
                : $axis;
            $values['columns']['newUsers'][] = (isset($count['newUsers'])) ? $count['newUsers'] : 0;
            $values['columns']['activeUsers'][] = (isset($count['activeUsers'])) ? $count['activeUsers'] : 0;
            $values['columns']['totalUsers'][] = (isset($count['totalUsers'])) ? $count['totalUsers'] : 0;
        }

        $format = $request->getRequestFormat();
        if ($format === 'csv') {
            return $this->toCSV($request, $values, 'Year');
        }

        $view = $this->view($values, 200)->setFormat('json');
        return $this->viewHandler->handle($view);
    }

    /**
     * GET Route annotation.
     * @Get("/requests_origin.{_format}", name="public_rest_get_requests_origin_data", options={"expose"=true}, defaults={"_format"="json"})
     */
    public function getRequestsOriginCountData(Request $request)
    {
        $translator = $this->translator;

        $instance = $request->query->get('instance');
        $type = $request->query->get('type');
        $country = $request->query->get('country');
        $institution = $request->query->get('institution');
        $initialYear = $request->query->get('initialYear');
        $finalYear = $request->query->get('finalYear');

        $counts = $this->entityManager->getRepository(Institution::class)
                ->countRequestsOrigin($instance, $type, $initialYear, $finalYear, $country, $institution);

        uasort($counts, function($a, $b) {
            if ($a['requestsCount'] === $b['requestsCount']) {
                return 0;
            }
            return ($a['requestsCount'] > $b['requestsCount']) ? -1 : 1;
        });

        $data = array();
        $data['columns']['requestsCount'][] = $translator->trans('Requests');
        $data['categories'] = Array();
        foreach ($counts as $count) {
            $data['columns']['requestsCount'][] = $count['requestsCount'];
            $data['countries'][] = (Integer) $count['institutionCountry'];
            $data['categories'][] = $count['name'];
            $data['ids'][] = (Integer) $count['id'];
        }

        $format = $request->getRequestFormat();
        if ($format === 'csv') {
            return $this->toCSV($request, $data, 'Country');
        }

        $view = $this->view($data, 200)->setFormat('json');
        return $this->viewHandler->handle($view);
    }

    /**
     * GET Route annotation.
     * @Get("/requests_count.{_format}", name="public_rest_get_requests_count_data_for", options={"expose"=true}, defaults={"_format"="json"})
     */
    public function getRequestsCountDataFor(Request $request)
    {
        $translator = $this->translator;

        $instance = $request->query->get('instance');
        $type = $request->query->get('type');
        $initialYear = $request->query->get('initialYear');
        $finalYear = $request->query->get('finalYear');

        $result = $this->entityManager
                ->getRepository(State::class)
                ->findRequestsStateCountFor($instance, $type, $initialYear, $finalYear);

        $rows = array();
        foreach ($result as $count) {
            $axis = ($initialYear === $finalYear) ?
                $translator->trans(strftime('%B', mktime(0, 0, 0, $count['axisValue'], 10)))
                : $count['axisValue'];

            $rows[$axis][$count['stateType']]['requestCount'] = $count['requestsCount'];
        }

        $values = array();
        $values['columns']['created'][] = $translator->trans('Created');
        $values['columns']['cancelled'][] = $translator->trans('Cancelled');
        $values['columns']['satisfied'][] = $translator->trans('Satisfied');
        $values['columns']['searched'][] = $translator->trans('Searched_s');
        $values['columns']['annulled'][] = $translator->trans('Annulled_s');
        foreach ($rows as $key => $row) {
            $values['categories'][] = $key;
            $values['columns']['created'][] = (isset($row['created'])) ? $row['created']['requestCount'] : 0;
            $values['columns']['cancelled'][] = (isset($row['cancelled'])) ? $row['cancelled']['requestCount'] : 0;
            $values['columns']['satisfied'][] = (isset($row['received'])) ? $row['received']['requestCount'] : 0;
            $values['columns']['searched'][] = (isset($row['searched'])) ? $row['searched']['requestCount'] : 0;
            $values['columns']['annulled'][] = (isset($row['annulled'])) ? $row['annulled']['requestCount'] : 0;
        }

        $format = $request->getRequestFormat();
        if ($format === 'csv') {
            return $this->toCSV($request, $values, 'Year');
        }

        $view = $this->view($values, 200)->setFormat('json');
        return $this->viewHandler->handle($view);
    }

    private function toCSV(Request $request, $data, $firstColumn = '') {
        $response = $this->render('Public/_statistics.csv.twig', ['data' => $data, 'firstColumn' => $firstColumn]);
        $filename = preg_replace('/_public_rest/', '', preg_replace('/\.csv/', '_' . date("YmdHis") . '.csv', preg_replace('/\//', '_', preg_replace('/\//', '', $request->getPathInfo(), 1))));
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$filename);

        return $response;
    }

    /**
     * GET Route annotation.
     * @Get("/requests_destiny_distribution.{_format}", name="public_rest_get_requests_destiny_distribution_data_for", options={"expose"=true}, defaults={"_format"="json"})
     */
    public function getRequestsDestinyDistributionDataFor(Request $request)
    {
        $translator = $this->translator;

        $instance = $request->query->get('instance');
        $initialYear = intval($request->query->get('initialYear'));
        $finalYear = intval($request->query->get('finalYear'));
        $type = $request->query->get('type');

        $eventRepository = $this->entityManager->getRepository(Event::class);

        $values = array();

        $created = $eventRepository->findCreatedRequestDestinyDistributionFor($instance, $type, $initialYear, $finalYear);
        foreach ($created as $count) {
            $values[$count['countryName']]['created'][] = $count['requestsCount'];
        }
        $cancelled = $eventRepository->findCancelledRequestDestinyDistributionFor($instance, $type, $initialYear, $finalYear);
        foreach ($cancelled as $count) {
            $values[$count['countryName']]['cancelled'][] = $count['requestsCount'];
        }
        $delivered = $eventRepository->findDeliveredRequestDestinyDistributionFor($instance, $type, $initialYear, $finalYear);
        foreach ($delivered as $count) {
            $values[$count['countryName']]['delivered'][] = $count['requestsCount'];
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
        $data['columns']['created'][] = $translator->trans('Created');
        $data['columns']['cancelled'][] = $translator->trans('Cancelled');
        $data['columns']['delivered'][] = $translator->trans('Delivered');
        foreach ($values as $key => $val) {
            $data['categories'][] = $key;
            $data['columns']['created'][] = (isset($val['created'])) ? $val['created'][0] : 0;
            $data['columns']['cancelled'][] = (isset($val['cancelled'])) ? $val['cancelled'][0] : 0;
            $data['columns']['delivered'][] = (isset($val['delivered'])) ? $val['delivered'][0] : 0;
        }

        $format = $request->getRequestFormat();
        if ($format === 'csv') {
            return $this->toCSV($request, $data, 'Country');
        }

        $view = $this->view($data, 200)->setFormat('json');
        return $this->viewHandler->handle($view);
    }

    /**
     * GET Route annotation.
     * @Get("/requests_number_by_publication_year.{_format}", name="public_rest_get_requests_number_by_publication_year_data_for", options={"expose"=true}, defaults={"_format"="json"})
     */
    public function getRequestsNumberByPublicationYearDataFor(Request $request)
    {
        $instance = $request->query->get('instance');
        $initialYear = $request->query->get('initialYear');
        $finalYear = $request->query->get('finalYear');
        $type = $request->query->get('type');

        $result = $this->entityManager
                ->getRepository(\Celsius3\CoreBundle\Entity\Request::class)
                ->findRequestsNumberByPublicationYearFor($instance, $type, $initialYear, $finalYear);

        $data = array();
        $data['columns']['counts'][] = 'Cantidad';
        $data['categories'][0] = '< 1950';
        $data['columns']['counts'][0] = 0;
        foreach ($result as $row) {
            if($row['materialDataYear'] <= 1950) {
                $data['columns']['counts'][0] += $row['materialDataCount'];
            } else {
                $data['categories'][] = $row['materialDataYear'];
                $data['columns']['counts'][] = $row['materialDataCount'];
            }
        }
        if (array_key_exists('categories', $data) && $count = count($data['categories']) > 0) {
            $div = $count * 0.1;
            for ($i = 1; $i <= (count($data['categories']) / $div); $i++) {
                $data['tickValue'][] = $i * $div;
            }
        }

        $format = $request->getRequestFormat();
        if ($format === 'csv') {
            return $this->toCSV($request, $data, 'Publication year');
        }

        $view = $this->view($data, 200)->setFormat('json');
        return $this->viewHandler->handle($view);
    }

    /**
     * GET Route annotation.
     * @Get("/requests_total_delay.{_format}", name="public_rest_get_requests_total_delay_data_for", options={"expose"=true}, defaults={"_format"="json"})
     */
    public function getRequestsTotalDelayDataFor(Request $request)
    {
        $translator = $this->translator;

        $instance = $request->query->get('instance');
        $initialYear = $request->query->get('initialYear');
        $finalYear = $request->query->get('finalYear');
        $type = $request->query->get('type');
        $delayType = $request->query->get('delayType');
        $result = $this->entityManager->getRepository(\Celsius3\CoreBundle\Entity\Request::class)->findRequestsDelay($instance, $type, $initialYear, $finalYear, $delayType);

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
        $data['columns']['delay0'][] = $d0 = $translator->trans('delay0');
        $data['columns']['delay1'][] = $d1 = $translator->trans('delay1');
        $data['columns']['delay2'][] = $d2 = $translator->trans('delay2');
        $data['columns']['delay3'][] = $d3 = $translator->trans('delay3');
        $data['columns']['delay4'][] = $d4 = $translator->trans('delay4');
        $data['columns']['delay5'][] = $d5 = $translator->trans('delay5');
        $data['columns']['delay6'][] = $d6 = $translator->trans('delay6');
        $data['columns']['delay7'][] = $d7 = $translator->trans('delay7');
        $data['columns']['delay8'][] = $d8 = $translator->trans('delay8');
        $data['columns']['delay9'][] = $d9 = $translator->trans('delay9');

        $data['groups'] = [$d0, $d1, $d2, $d3, $d4, $d5, $d6, $d7, $d8, $d9];

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

        $format = $request->getRequestFormat();
        if ($format === 'csv') {
            return $this->toCSV($request, $data, 'Year');
        }

        $view = $this->view($data, 200)->setFormat('json');
        return $this->viewHandler->handle($view);
    }

}
