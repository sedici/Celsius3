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
        //Al no crear una entidad estadistica. No es necesario pedir en la misma
        //consulta mes y año. Separarlas en dos consultas para usar cuando se
        //visualicen los años y otra para cuando se visualice un año especifico.
        //***Cantidad de usuarios nuevos por mes y año
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();
        $qb->select('u.createdAt.getFullYear(), COUNT(DISTINCT(u.id))')
                ->from('User', 'u')
                ->groupBy('u.createdAt.getFullYear()')
                ->orderBy('u.createdAt.getFullYear()', 'ASC');
        $resultQB = $qb->getQuery()->getResult();

        $dql = "SELECT u.createdAt.getFullYear(), u.createdAt.getMonth(), COUNT(u.id)
        FROM Celsius3CoreBundle:BaseUser u
        GROUP BY u.createdAt.getFullYear(), u.createdAt.getMonth()";
        $query = $em->createQuery($dql);
        $resultDQL = $query->getResult();
        //***********************//
        //***Consulta de usuarios activos por mes y año***//
        $qb = $em->createQueryBuilder();
        $qb->select('r.createdAt.getFullYear(), r.createdAt.getMonth(), u.id')
                ->from('User', 'u')
                ->distinct('u.id')
                ->groupBy('u.createdAt.getFullYear()')
                ->addGroupBy('u.createdAt.getMonth()')
                ->orderBy('u.createdAt.getFullYear()', 'ASC')
                ->addOrderBy('u.createdAt.getMonth()', 'ASC');
        $resultQB = $qb->getQuery()->getResult();
        //*************************************//


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

        $newUsers = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:BaseUser')->countNewUsersForInterval($initialYear, $finalYear);
        $activeUsers = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Request')->countActiveUsersForInterval($initialYear, $finalYear);

        $result = array();
        $suma = 0;
        foreach ($newUsers as $count) {
            $result[$count['year']]['newUsers'] = $count['newUsers'];
            $result[$count['year']]['totalUsers'] = $suma += $count['newUsers'];
        }
        foreach ($activeUsers as $count) {
            $result[$count['year']]['activeUsers'] = $count['activeUsers'];
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
