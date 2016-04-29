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

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Celsius3\CoreBundle\Exception\Exception;

/**
 * Search controller
 *
 * @Route("/admin/search")
 */
class AdminSearchController extends BaseInstanceDependentController
{

    /**
     * @Route("/", name="admin_search")
     * @Template()
     *
     * @return array
     */
    public function searchAction(Request $request)
    {
        $keyword = $request->query->get('keyword');
        $filters = $request->query->get('filters', []);
        $searchManager = $this->container->get('celsius3_core.search_manager');

        if (!$searchManager->validate($keyword)) {
            throw Exception::create(Exception::INVALID_SEARCH, 'exception.invalid.search');
        }

        $delFilter = $request->query->get('del-filter', []);
        if (!empty($delFilter) && array_key_exists($delFilter['name'], $filters)) {
            unset($filters[$delFilter['name']]);
        }

        $addFilter = $request->query->get('add-filter', []);
        if (!empty($addFilter) && !array_key_exists($addFilter['name'], $filters)) {
            $filters[$addFilter['name']] = $addFilter['value'];
        }

        $results = $searchManager->search($keyword, $filters, $this->getInstance());

        $aggregations = $results->getAggregations();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($results, $this->get('request')->query->get('page', 1), $this->container->getParameter('max_per_page'));

        $users = $searchManager->getAggsUsersData($aggregations);

        return array(
            'keyword' => $keyword,
            'pagination' => $pagination,
            'aggregations' => $aggregations,
            'filters' => $filters,
            'users' => $users
        );
    }

}
