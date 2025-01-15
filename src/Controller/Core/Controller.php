<?php

/*
 * Celsius3 - Core controller
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

namespace Celsius3\Controller\Core;

use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Entity\Instance;
use Celsius3\Exception\Exception;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class Controller extends AbstractController
{

    protected InstanceManager $instanceManager;
    protected EntityManagerInterface $entityManager;
    protected ConfigurationHelper $configurationHelper;
    protected PaginatorInterface $paginator;
    protected TranslatorInterface $translator;
    protected ManagerRegistry $managerRegistry;
    protected RequestStack $requestStack;
    protected UnionManager $unionManager;
    protected UserManager $userManager;
    protected FilterManager $filterManager;
    protected InstanceHelper $instanceHelper;
    protected Instance $directory;
    protected ObjectManager $objectManager;
    protected EntityRepository $repository;
    protected Instance $instance;

    public function __construct(
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
        $this->instanceManager = $instanceManager;
        $this->entityManager = $entityManager;
        $this->configurationHelper = $configurationHelper;
        $this->paginator = $paginator;
        $this->translator = $translator;
        $this->managerRegistry = $managerRegistry;
        $this->requestStack = $requestStack;
        $this->unionManager = $unionManager;
        $this->userManager = $userManager;
        $this->filterManager = $filterManager;
        $this->instanceHelper = $instanceHelper;

        $this->initialize();
    }


    public function initialize(): void
    {
        $this->objectManager = $this->managerRegistry->getManager();
        $this->directory = $this->getDirectory();
        $this->instance = $this->getInstance();
    }


    protected function printVar($entity): void {
        $str = '{ ';
        foreach ($entity as $property => $value) {
            $str .= "$property => $value, ";
        }
        $str .= ' }';
        throw new \Exception($str);
    }


    protected function getInstance(): Instance
    { return $this->instanceHelper->getSessionOrUrlInstance(); }


    protected function getDirectory(): Instance|null
    { return $this->instanceManager->getDirectory(); }

    
    protected function listQuery(): QueryBuilder
    { return $this->repository->createQueryBuilder('e'); }


    protected function findQuery(string $id)
    { return $this->repository->find($id); }


    protected function getResultsPerPage(): mixed
    {
        return $this
            ->configurationHelper
            ->getCastedValue(
                $this->directory
                    ->get('results_per_page')
            );
    }


    protected function error(
        string $type,
        string $entity = '',
        ?string $msg = null
    ): never {
        $msg = (string) 'exception.' . $type . $entity;
        throw Exception::create($type, $msg);
    }


    protected function paginate(
        ?QueryBuilder $query = null,
        ?Request $request = null,
        ?int $page = null,
        ?int $limit = null,
        ?array $options = null
    ): PaginationInterface {
        if ($request === null)
            $request = $this->requestStack->getCurrentRequest();

        if ($query === null) $query = $this->listQuery();

        if ($page === null)
            $page = intval($request->query->get('page', 1));

        if ($limit === null) $limit = $this->getResultsPerPage();

        return $this->paginator->paginate(
            $query,
            $page,
            $limit,
            $options
        );
    }


    protected function createDeleteForm(string $id): FormInterface
    {
        return $this
            ->createFormBuilder([
                'id' => $id,
            ])
            ->add('id', HiddenType::class)
            ->getForm();
    }
}