<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Entity\Journal;
use Celsius3\CoreBundle\Form\Type\JournalType;
use Celsius3\CoreBundle\Filter\Type\JournalFilterType;

/**
 * Location controller.
 *
 * @Route("/admin/journal")
 */
class AdminJournalController extends BaseInstanceDependentController
{

    /**
     * Lists all Journal entities.
     *
     * @Route("/", name="admin_journal")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('Journal', $this->createForm(new JournalFilterType($this->getInstance())));
    }

    /**
     * Displays a form to create a new Journal entity.
     *
     * @Route("/new", name="admin_journal_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('Journal', new Journal(), new JournalType($this->getInstance()));
    }

    /**
     * Creates a new Journal entity.
     *
     * @Route("/create", name="admin_journal_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminJournal:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Journal', new Journal(), new JournalType($this->getInstance()), 'admin_journal');
    }

    /**
     * Displays a form to edit an existing Journal entity.
     *
     * @Route("/{id}/edit", name="admin_journal_edit")
     * @Template()
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('Journal', $id, new JournalType($this->getInstance()));
    }

    /**
     * Edits an existing Journal entity.
     *
     * @Route("/{id}/update", name="admin_journal_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminJournal:edit.html.twig")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('Journal', $id, new JournalType($this->getInstance()), 'admin_journal');
    }
}
