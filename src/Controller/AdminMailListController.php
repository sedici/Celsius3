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

namespace Celsius3\Controller;

use Celsius3\Entity\Email;
use Celsius3\Form\Type\Filter\MailFilterType;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\FilterManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * MailList controller.
 *
 * @Route("/admin/maillist")
 */
class AdminMailListController extends AbstractController
{

    /**
     * @var InstanceHelper
     */
    private $instanceHelper;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var FilterManager
     */
    private $filterManager;
    /**
     * @var ConfigurationHelper
     */
    private $configurationHelper;
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(
        InstanceHelper $instanceHelper,
        EntityManagerInterface $entityManager,
        FilterManager $filterManager,
        ConfigurationHelper $configurationHelper,
        PaginatorInterface $paginator
    ) {
        $this->instanceHelper = $instanceHelper;
        $this->entityManager = $entityManager;
        $this->filterManager = $filterManager;
        $this->configurationHelper = $configurationHelper;
        $this->paginator = $paginator;
    }

    protected function listQuery()
    {
        return $this->entityManager
            ->getRepository(Email::class)
            ->createQueryBuilder('e')
            ->andWhere('e.instance = :instance_id')
            ->setParameter('instance_id', $this->instanceHelper->getSessionOrUrlInstance()->getId());
    }

    /**
     * Lists all Mail entities.
     *
     * @Route("/", name="admin_maillist")
     */
    public function index(Request $request): Response
    {
        $filterForm = $this->createForm(MailFilterType::class, null, [
            'instance' => $this->instanceHelper->getSessionOrUrlInstance(),
        ]);

        $query = $this->listQuery();

        if ($filterForm !== null) {
            $filterForm = $filterForm->handleRequest($request);
            $query = $this->filterManager->filter($query, $filterForm, Email::class);
        }

        $pagination = $this->paginator->paginate(
            $query,
            $request->query->get('page', 1),
            $this->getResultsPerPage(),
            [
                'defaultSortFieldName' => 'e.updatedAt',
                'defaultSortDirection' => 'desc',
            ]
        );

        return $this->render(
            'Admin/MailList/index.html.twig',
            [
                'pagination' => $pagination,
                'filter_form' => ($filterForm !== null) ? $filterForm->createView() : $filterForm,
            ]
        );
    }

    protected function getResultsPerPage()
    {
        return $this->configurationHelper
            ->getCastedValue($this->instanceHelper->getSessionOrUrlInstance()->get('results_per_page'));
    }
}
