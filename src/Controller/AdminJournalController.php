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

use Celsius3\Entity\Event\Event;
use Celsius3\Entity\Journal;
use Celsius3\Exception\Exception;
use Celsius3\Form\Type\Filter\JournalFilterType;
use Celsius3\Form\Type\JournalType;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\CatalogManager;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\InstanceManager;
use Celsius3\Manager\UserManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Location controller.
 *
 * @Route("/admin/journal")
 */
class AdminJournalController extends AbstractController
{
    /**
     * @var InstanceManager
     */
    private $instanceManager;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ConfigurationHelper
     */
    private $configurationHelper;
    /**
     * @var FilterManager
     */
    private $filterManager;
    /**
     * @var PaginatorInterface
     */
    private $paginator;
    /**
     * @var InstanceHelper
     */
    private $instanceHelper;
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(
        InstanceManager $instanceManager,
        EntityManagerInterface $entityManager,
        ConfigurationHelper $configurationHelper,
        FilterManager $filterManager,
        PaginatorInterface $paginator,
        InstanceHelper $instanceHelper,
        UserManager $userManager,
        Security $security,
        TranslatorInterface $translator
    ) {
        $this->instanceManager = $instanceManager;
        $this->entityManager = $entityManager;
        $this->configurationHelper = $configurationHelper;
        $this->filterManager = $filterManager;
        $this->paginator = $paginator;
        $this->instanceHelper = $instanceHelper;
        $this->userManager = $userManager;
        $this->security = $security;
        $this->translator = $translator;
    }

    protected function listQuery()
    {
        return $this->entityManager
            ->getRepository(Journal::class)
            ->findForInstanceAndGlobal(
                $this->instanceHelper->getSessionOrUrlInstance(),
                $this->instanceManager->getDirectory()
            );
    }

    protected function findShowQuery($id)
    {
        return $this->getDoctrine()->getManager()
            ->getRepository(Journal::class)
            ->findOneForInstanceOrGlobal(
                $this->instanceHelper->getSessionOrUrlInstance(),
                $this->instanceManager->getDirectory(),
                $id
            );
    }

    protected function getResultsPerPage()
    {
        return $this->configurationHelper
            ->getCastedValue(
                $this->instanceHelper->getSessionOrUrlInstance()->get('results_per_page')
            );
    }

    protected function getSortDefaults()
    {
        return [
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        ];
    }

    /**
     * Lists all Journal entities.
     *
     * @Route("/", name="admin_journal")
     */
    public function index(Request $request): Response
    {
        $query = $this->listQuery();

        $filterForm = $this->createForm(JournalFilterType::class, null, [
            'instance' => $this->instanceHelper->getSessionOrUrlInstance(),
        ]);

        if ($filterForm !== null) {
            $filterForm = $filterForm->handleRequest($request);
            $query = $this->filterManager->filter($query, $filterForm, Journal::class);
        }

        $pagination = $this->paginator->paginate(
            $query,
            $request->query->get('page', 1),
            $this->getResultsPerPage(),
            $this->getSortDefaults()
        );

        return $this->render(
            'Admin/Journal/index.html.twig',
            [
                'pagination' => $pagination,
                'filter_form' => ($filterForm !== null) ? $filterForm->createView() : $filterForm,
            ]
        );
    }

    /**
     * Displays data for a Journal.
     *
     * @Route("/{id}/show", name="admin_journal_show", options={"expose"=true})
     */
    public function show($id): Response
    {
        $entity = $this->findShowQuery($id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.journal');
        }
        $receptions = $this->getDoctrine()->getRepository(Event::class)
            ->getPreviousJournalReceivedRequests($this->instanceHelper->getSessionOrUrlInstance(), $entity);
        $results = $this->getDoctrine()->getRepository(Event::class)
            ->getPreviousJournalSearches($this->instanceHelper->getSessionOrUrlInstance(), $entity);

        $searches = [
            CatalogManager::CATALOG__FOUND => [],
            CatalogManager::CATALOG__PARTIALLY_FOUND => [],
        ];
        foreach ($results as $search) {
            $searches[$search->getResult()][] = $search;
        }

        return $this->render('Admin/Journal/show.html.twig', [
            'entity' => $entity,
            'searches' => $searches,
            'receptions' => $receptions,
        ]);
    }

    /**
     * Displays a form to create a new Journal entity.
     *
     * @Route("/new", name="admin_journal_new", options={"expose"=true})
     */
    public function new(): Response
    {
        $entity = new Journal();

        $form = $this->createForm(
            JournalType::class,
            $entity,
            ['instance' => $this->instanceHelper->getSessionOrUrlInstance()]
        );

        return $this->render(
            'Admin/Journal/new.html.twig',
            [
                'entity' => $entity,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Creates a new Journal entity.
     *
     * @Route("/create", name="admin_journal_create", methods={"POST"})
     *
     */
    public function create(Request $request)
    {
        $entity = new Journal();

        $form = $this->createForm(
            JournalType::class,
            $entity,
            ['instance' => $this->instanceHelper->getSessionOrUrlInstance()]
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $this->persistEntity($entity);
                $this->addFlash(
                    'success',
                    $this->translator->trans(
                        'The %entity% was successfully created.',
                        ['%entity%' => $this->translator->trans('Journal')],
                        'Flashes'
                    )
                );

                return $this->redirect($this->generateUrl('admin_journal'));
            } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                $this->addFlash(
                    'error',
                    $this->translator->trans(
                        'The %entity% already exists.',
                        ['%entity%' => $this->translator->trans('Journal')],
                        'Flashes'
                    )
                );
            }
        }

        $this->addFlash(
            'error',
            $this->translator->trans(
                'There were errors creating the %entity%.',
                ['%entity%' => $this->translator->trans('Journal')],
                'Flashes'
            )
        );

        return $this->render(
            'Admin/Journal/new.html.twig',
            [
                'entity' => $entity,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Journal entity.
     *
     * @Route("/{id}/edit", name="admin_journal_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id): Response
    {
        $entity = $this->findQuery($id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND);
        }

        $editForm = $this->createForm(JournalType::class, $entity, [
            'instance' => $this->instanceHelper->getSessionOrUrlInstance(),
        ]);

        return $this->render(
            'Admin/Journal/edit.html.twig',
            [
                'entity' => $entity,
                'edit_form' => $editForm->createView()
            ]
        );
    }

    /**
     * Edits an existing Journal entity.
     *
     * @Route("/{id}/update", name="admin_journal_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update(Request $request, $id)
    {
        $entity = $this->findQuery($id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND);
        }

        $editForm = $this->createForm(
            JournalType::class,
            $entity,
            [
                'instance' => $this->instanceHelper->getSessionOrUrlInstance()
            ]
        );

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            try {
                $this->persistEntity($entity);

                $this->addFlash(
                    'success',
                    $this->translator->trans(
                        'The %entity% was successfully edited.',
                        ['%entity%' => $this->translator->trans('Journal')],
                        'Flashes'
                    )
                );

                return $this->redirect($this->generateUrl('admin_journal_edit', ['id' => $id]));
            } catch (UniqueConstraintViolationException $exception) {
                $this->addFlash(
                    'error',
                    $this->translator->trans(
                        'The %entity% already exists.',
                        ['%entity%' => $this->translator->trans('Journal')],
                        'Flashes'
                    )
                );
            }
        }

        $this->addFlash(
            'error',
            $this->translator->trans(
                'There were errors editing the %entity%.',
                ['%entity%' => $this->translator->trans('Journal')],
                'Flashes'
            )
        );

        return $this->render(
            'Admin/Journal/edit.html.twig',
            [
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            ]
        );
    }

    protected function findQuery($id)
    {
        $isAdmin = $this->userManager->getCurrentRole($this->security->getUser()) === 'ROLE_SUPER_ADMIN';

        return $this->entityManager
            ->getRepository(Journal::class)
            ->findQuery($this->instanceHelper->getSessionOrUrlInstance(), $id, $isAdmin);
    }

    protected function persistEntity($entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
