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

namespace Celsius3\Controller\Html;

use Celsius3\Form\Type\Filter\MailFilterType;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\MailListController;

/**
 * MailList controller.
 * @Route("/admin/maillist")
 */
class HtmlAdminMailListController extends MailListController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->htmlRenderer->setTemplatePrefix('Admin/MailList/');
    }


    public function listQuery(?bool $isInstanceDependent = null): QueryBuilder
    {
        return $this->entityManager
            ->getRepository($this->entityClassName)
            ->createQueryBuilder('e')
            ->andWhere('e.instance = :instance_id')
            ->setParameter(
                'instance_id',
                $this->instance->getId()
            );
    }


    // protected function getResultsPerPage()
    // {
    //     return $this->configurationHelper
    //         ->getCastedValue(
    //             $this->instanceHelper
    //                 ->getSessionOrUrlInstance()
    //                 ->get('results_per_page')
    //             );
    // }


    /**
     * Lists all Mail entities.
     * @Route("/", name="admin_maillist")
     */
    public function htmlIndex(): Response
    {
        return $this->htmlRenderer->render(
            'index', $this->index(MailFilterType::class)
        );
    }
}
