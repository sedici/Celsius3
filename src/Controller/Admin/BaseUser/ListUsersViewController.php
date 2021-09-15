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

declare(strict_types=1);

namespace Celsius3\Controller\Admin\BaseUser;

use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\Form\Type\Filter\BaseUserFilterType;
use Celsius3\CoreBundle\Helper\ConfigurationHelper;
use Celsius3\CoreBundle\Helper\InstanceHelper;
use Celsius3\Manager\FilterManager;
use Celsius3\Repository\BaseUserRepositoryInterface;
use Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ListUsersViewController extends AbstractController
{
    private $baseUserRepository;
    private $instanceHelper;
    private $filterManager;
    private $configurationHelper;
    private $paginator;

    public function __construct(
        BaseUserRepositoryInterface $baseUserRepository,
        InstanceHelper $instanceHelper,
        FilterManager $filterManager,
        ConfigurationHelper $configurationHelper,
        Paginator $paginator
    ) {
        $this->baseUserRepository = $baseUserRepository;
        $this->instanceHelper = $instanceHelper;
        $this->filterManager = $filterManager;
        $this->configurationHelper = $configurationHelper;
        $this->paginator = $paginator;
    }

    public function __invoke(Request $request): Response
    {
        $query = $this->baseUserRepository->createQueryBuilder('e');
        $filterForm = $this->createForm(
            BaseUserFilterType::class,
            null,
            [
                'instance' => $this->instanceHelper->getSessionInstance(),
            ]
        );

        if ($filterForm !== null) {
            $filterForm = $filterForm->handleRequest($request);
            $query = $this->filterManager
                ->filter($query, $filterForm, BaseUser::class, $this->instanceHelper->getSessionInstance());
        }

        $pagination = $this->paginator->paginate(
            $query,
            $request->query->get('page', 1),
            $this->configurationHelper
                ->getCastedValue($this->instanceHelper->getSessionInstance()->get('results_per_page')),
            [
                'defaultSortFieldName' => 'e.surname',
                'defaultSortDirection' => 'asc',
            ]
        );

        return $this->render('Admin/BaseUser/index.html.twig', [
            'pagination' => $pagination,
            'filter_form' => $filterForm->createView(),
        ]);
    }
}
