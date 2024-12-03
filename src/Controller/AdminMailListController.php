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
use Celsius3\Manager\FilterManager;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Response;

/**
 * MailList controller.
 *
 * @Route("/admin/maillist")
 */
class AdminMailListController extends BaseInstanceDependentController
{
    /**
     * @var FilterManager
     */
    private $filterManager;

    public function __construct(
        FilterManager $filterManager,
        ...$args
    ) {
        parent::__construct(...$args);
        $this->filterManager = $filterManager;
    }

    protected final function getEntity(): string
    { return Email::class; }

    protected final function getType(): string
    { return EmailType::class; }

    protected final function getTemplatePrefix(): string
    { return 'Admin/MailList/'; }


    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.updatedAt',
            'defaultSortDirection' => 'desc'
        ];
    }


    protected function listQuery(): QueryBuilder
    {
        return $this->entityManager
            ->getRepository($this->entityClassName)
            ->createQueryBuilder('e')
            ->andWhere('e.instance = :instance_id')
            ->setParameter(
                'instance_id',
                $this->instanceHelper
                    ->getSessionOrUrlInstance()->getId()
            );
    }


    protected function getResultsPerPage()
    {
        return $this->configurationHelper
            ->getCastedValue(
                $this->instanceHelper
                    ->getSessionOrUrlInstance()
                    ->get('results_per_page')
                );
    }


    /**
     * Lists all Mail entities.
     *
     * @Route("/", name="admin_maillist")
     */
    public function index(): Response
    {
        return $this->baseIndex(
            type: MailFilterType::class,
            options: [ 
                'instance' => $this->instanceHelper->getSessionOrUrlInstance()
            ]
        );
    }
}
