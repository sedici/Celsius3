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

namespace Celsius3\Controller\Web;

use Celsius3\Manager\SearchManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\BaseInstanceDependentController;

use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Search controller
 *
 * @Route("/admin/search")
 */
class AdminSearchController extends BaseInstanceDependentController
{

    /** 
      * @var SearchManager
      */
    protected $searchManager;
    
    public function __construct(
        SearchManager $searchManager,
        InstanceManager $instanceManager,
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
        ConfigurationHelper $configurationHelper,
        TranslatorInterface $translator,
        ManagerRegistry $managerRegistry,
        RequestStack $requestStack,
        UnionManager $unionManager,
        UserManager $userManager,
        FilterManager $filterManager,
        InstanceHelper $instanceHelper
    ) {
        parent::__construct(
            $instanceManager,
            $entityManager,
            $paginator,
            $configurationHelper,
            $translator,
            $managerRegistry,
            $requestStack,
            $unionManager,
            $userManager,
            $filterManager,
            $instanceHelper
        );

        $this->searchManager = $searchManager;
    }


    protected function getEntity(): string
    { return Request::class; }

    final protected function getTemplatePrefix(): string
    { return 'Admin/Search/'; }


    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.updatedAt',
            'defaultSortDirection' => 'desc',
        ];
    }


    /**
     * @Route("/", name="admin_search")
     */
    public function search(): Response
    {
        $request = $this->requestStack->getCurrentRequest();
        
        $keyword = $request->query->get('keyword');
        $filters = (array) $request->query->get('filters', []);
        $searchManager = $this->instanceHelper;

        $delFilter = $request->query->get('del-filter', []);
        if (
            !empty($delFilter)
            && array_key_exists($delFilter['name'], $filters)
        ) {
            unset($filters[$delFilter['name']]);
        }

        $addFilter = $request->query->get('add-filter', []);
        if (
            !empty($addFilter)
            && !array_key_exists($addFilter['name'], $filters)
        ) {
            $filters[$addFilter['name']] = $addFilter['value'];
        }

        $results = $this->searchManager->search(
            $keyword, $filters, $this->instance
        );

        $aggregations = $results->getAggregations();
        $pagination = $this->paginator->paginate(
            $results,
            intval($request->query->get('page', 1)),
            $this->configurationHelper
                ->getCastedValue(
                    $this->getInstance()->get('results_per_page')
                )
        );

        $users = $this->searchManager->getAggsUsersData($aggregations);

        return $this->render(
            (string) $this->templatePrefix . 'search.html.twig',
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
