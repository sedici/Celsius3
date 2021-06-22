<?php

declare(strict_types=1);

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

namespace Celsius3\Controller\Admin\Journal;

use Celsius3\CoreBundle\Entity\Journal;
use Celsius3\CoreBundle\Form\Type\Filter\JournalFilterType;
use Celsius3\CoreBundle\Helper\ConfigurationHelper;
use Celsius3\CoreBundle\Helper\InstanceHelper;
use Celsius3\CoreBundle\Manager\FilterManager;
use Celsius3\CoreBundle\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ListJournalsGetController extends AbstractController
{
    private $instanceHelper;
    private $filterManager;
    private $instanceManager;
    private $journalRepository;
    private $paginator;
    private $configurationHelper;

    public function __construct(
        EntityManagerInterface $entityManager,
        InstanceHelper $instanceHelper,
        FilterManager $filterManager,
        InstanceManager $instanceManager,
        PaginatorInterface $paginator,
        ConfigurationHelper $configurationHelper
    ) {
        $this->instanceHelper = $instanceHelper;
        $this->filterManager = $filterManager;
        $this->instanceManager = $instanceManager;
        $this->journalRepository = $entityManager->getRepository(Journal::class);
        $this->paginator = $paginator;
        $this->configurationHelper = $configurationHelper;
    }

    public function __invoke(Request $request): Response
    {
        $instance = $this->instanceHelper->getSessionInstance();

        $filter_form = $this->createForm(
            JournalFilterType::class,
            null,
            [
                'instance' => $instance,
            ]
        );

        $query = $this->journalRepository
            ->findForInstanceAndGlobal(
                $instance,
                $this->instanceManager->getDirectory()
            );

        $filter_form->handleRequest($request);
        $query = $this->filterManager->filter(
            $query,
            $filter_form,
            Journal::class,
            $instance
        );

        $pagination = $this->paginator->paginate(
            $query,
            $request->query->get('page', 1),
            $this->configurationHelper->getCastedValue($instance->get('results_per_page')),
            $this->getSortDefaults()
        );

        return $this->render(
            'Admin/Journal/index.html.twig',
            [
                'pagination' => $pagination,
                'filter_form' => $filter_form->createView(),
            ]
        );
    }

    protected function getSortDefaults()
    {
        return [
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        ];
    }
}
