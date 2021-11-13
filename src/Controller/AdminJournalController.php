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

use Celsius3\CoreBundle\Entity\Event\Event;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Entity\Journal;
use Celsius3\Form\Type\JournalType;
use Celsius3\Form\Type\Filter\JournalFilterType;
use Celsius3\Manager\CatalogManager;
use Celsius3\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Location controller.
 *
 * @Route("/admin/journal")
 */
class AdminJournalController extends BaseInstanceDependentController
{
    protected function listQuery($name)
    {
        return $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3CoreBundle:'.$name)
                        ->findForInstanceAndGlobal($this->getInstance(), $this->getDirectory());
    }

    protected function findShowQuery($id)
    {
        return $this->getDoctrine()->getManager()
                    ->getRepository(Journal::class)
                    ->findOneForInstanceOrGlobal($this->getInstance(), $this->getDirectory(), $id);
    }

    protected function getSortDefaults()
    {
        return array(
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        );
    }

    /**
     * Lists all Journal entities.
     *
     * @Route("/", name="admin_journal")
     */
    public function index(): Response
    {
        return $this->render(
            'Admin/Journal/index.html.twig',
            $this->baseIndex(
                'Journal',
                $this->createForm(JournalFilterType::class, null, [
                    'instance' => $this->getInstance(),
                ])
            )
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
            ->getPreviousJournalReceivedRequests($this->getInstance(), $entity);
        $results = $this->getDoctrine()->getRepository(Event::class)
            ->getPreviousJournalSearches($this->getInstance(), $entity);

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
        return $this->render(
            'Admin/Journal/new.html.twig',
            $this->baseNew('Journal', new Journal(), JournalType::class, [
                'instance' => $this->getInstance(),
            ])
        );
    }

    /**
     * Creates a new Journal entity.
     *
     * @Route("/create", name="admin_journal_create", methods={"POST"})
     *
     */
    public function create()
    {
        return $this->render('Admin/Journal/new.html.twig', $this->baseCreate('Journal', new Journal(), JournalType::class, array(
                    'instance' => $this->getInstance(),
                        ), 'admin_journal'));
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
        return $this->render(
            'Admin/Journal/edit.html.twig',
            $this->baseEdit('Journal', $id, JournalType::class, [
                'instance' => $this->getInstance(),
            ])
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
    public function update($id)
    {
        return $this->render('Admin/Journal/edit.html.twig', $this->baseUpdate('Journal', $id, JournalType::class, array(
                    'instance' => $this->getInstance(),
                        ), 'admin_journal'));
    }

    protected function findQuery($name, $id)
    {
        $um = $this->container->get('celsius3_core.user_manager');
        $isAdmin = $um->getCurrentRole($this->getUser()) === 'ROLE_SUPER_ADMIN';

        return $this->getDoctrine()->getManager()
                ->getRepository($this->getBundle().':'.$name)
                ->findQuery($this->getInstance(), $id, $isAdmin);
    }
}
