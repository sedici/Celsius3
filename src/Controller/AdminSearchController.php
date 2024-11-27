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

use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\SearchManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Celsius3\Exception\Exception;
use Knp\Component\Pager\PaginatorInterface;
/**
 * Search controller
 *
 * @Route("/admin/search")
 */
class AdminSearchController extends BaseInstanceDependentController
{

    // /**
    //  * @var ConfigurationHelper
    //  */
    // private $searchManager;
    // /**
    //  * @var ConfigurationHelper
    //  */
    // private $instanceHelper;

    //  /**
    //  * @var PaginatorInterface
    //  */
    // private $paginator;

    // public function __construct(SearchManager $searchManager,InstanceHelper $instanceHelper,
    // PaginatorInterface $paginator,
    // ConfigurationHelper $configurationHelper)
    // {
    //     $this->paginator = $paginator;
    //     $this->searchManager=$searchManager;
    //     $this->instanceHelper=$instanceHelper;
    //     $this->configurationHelper = $configurationHelper;
    //     $this->setIntanceHelper($this->instanceHelper);


    // }


    /**
     * @Route("/", name="admin_search")
     */
    public function search(Request $request)
    {
        $keyword = $request->query->get('keyword');
        $filters = $request->query->get('filters', []);
        $searchManager = $this->instanceHelper;

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
       // $paginator = $this->get('knp_paginator');
        $pagination = $this->paginator->paginate(
            $results,
            intval($this->get('request_stack')->getCurrentRequest()->query->get('page', 1)),
            $this->configurationHelper
                ->getCastedValue(
                    $this->getInstance()->get('results_per_page')
                )
        );

        $users = $searchManager->getAggsUsersData($aggregations);

        return $this->render(
            'Admin/Search/search.html.twig',
            [
                'keyword' => $keyword,
                'pagination' => $pagination,
                'aggregations' => $aggregations,
                'filters' => $filters,
                'users' => $users
            ]
        );
    }

}
