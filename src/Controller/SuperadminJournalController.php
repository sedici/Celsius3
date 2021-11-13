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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Entity\Journal;
use Celsius3\Form\Type\JournalType;
use Celsius3\Form\Type\Filter\JournalFilterType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Location controller.
 *
 * @Route("/superadmin/journal")
 */
class SuperadminJournalController extends BaseController
{
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
     * @Route("/", name="superadmin_journal")
     */
    public function index(): Response
    {
        return $this->render(
            'Superadmin/Journal/index.html.twig',
            $this->baseIndex('Journal', $this->createForm(JournalFilterType::class))
        );
    }

    /**
     * Displays a form to create a new Journal entity.
     *
     * @Route("/new", name="superadmin_journal_new")
     */
    public function new(): Response
    {
        return $this->render(
            'Superadmin/Journal/new.html.twig',
            $this->baseNew('Journal', new Journal(), JournalType::class, [
                'instance' => $this->getDirectory(),
            ])
        );
    }

    /**
     * Creates a new Journal entity.
     *
     * @Route("/create", name="superadmin_journal_create", methods={"POST"})
     */
    public function create()
    {
        return $this->render('Superadmin/Journal/new.html.twig', $this->baseCreate('Journal', new Journal(), JournalType::class, array(
            'instance' => $this->getDirectory(),
        ), 'superadmin_journal'));
    }

    /**
     * Displays a form to edit an existing Journal entity.
     *
     * @Route("/{id}/edit", name="superadmin_journal_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id): Response
    {
        return $this->render(
            'Superadmin/Journal/edit.html.twig',
            $this->baseEdit('Journal', $id, JournalType::class, [
                'instance' => $this->getDirectory(),
            ])
        );
    }

    /**
     * Edits an existing Journal entity.
     *
     * @Route("/{id}/update", name="superadmin_journal_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update($id)
    {
        return $this->render('Superadmin/Journal/edit.html.twig', $this->baseUpdate('Journal', $id, JournalType::class, array(
            'instance' => $this->getDirectory(),
        ), 'superadmin_journal'));
    }

    /**
     * Batch actions.
     *
     * @Route("/batch", name="superadmin_journal_batch")
     *
     * @return array
     */
    public function batch()
    {
        return $this->baseBatch();
    }

    protected function batchUnion($element_ids)
    {
        return $this->render('Superadmin/Journal/batchUnion.html.twig', $this->baseUnion('Journal', $element_ids));
    }

    /**
     * Unifies a group of Journal entities.
     *
     * @Route("/doUnion", name="superadmin_journal_doUnion", methods={"POST"})
     */
    public function doUnion()
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $element_ids = $request->request->get('element');
        $main_id = $request->request->get('main');

        return $this->baseDoUnion(Journal::class, $element_ids, $main_id, 'superadmin_journal');
    }
}
