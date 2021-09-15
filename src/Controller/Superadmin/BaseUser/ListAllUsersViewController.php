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

namespace Celsius3\Controller\Superadmin\BaseUser;

use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\Form\Type\Filter\BaseUserFilterType;
use Celsius3\Manager\FilterManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ListAllUsersViewController extends AbstractController
{
    private $filterManager;
    private $entityManager;
    private $paginator;
    private $maxPerPage;

    public function __construct(
        FilterManager $filterManager,
        EntityManagerInterface $entityManager,
        Paginator $paginator,
        string $maxPerPage
    ) {
        $this->filterManager = $filterManager;
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
        $this->maxPerPage = $maxPerPage;
    }

    public function __invoke(Request $request): Response
    {
        $query = $this->listQuery();
        $filterForm = $this->createForm(BaseUserFilterType::class);

        $filterForm->handleRequest($request);
        $query = $this->filterManager
            ->filter($query, $filterForm, BaseUser::class);

        $pagination = $this->paginator->paginate(
            $query,
            $request->query->get('page', 1),
            $this->maxPerPage,
            $this->getSortDefaults()
        );

        return $this->render('Superadmin/BaseUser/index.html.twig', [
            'pagination' => $pagination,
            'filter_form' => $filterForm->createView()
        ]);
    }

    protected function listQuery()
    {
        return $this->entityManager
            ->getRepository(BaseUser::class)
            ->createQueryBuilder('e');
    }

    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.surname',
            'defaultSortDirection' => 'asc',
        ];
    }
}
