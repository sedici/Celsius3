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

use Celsius3\Entity\Instance;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Celsius3\Form\Type\Filter\MailTemplateFilterType;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

/**
 * Order controller.
 *
 * @Route("/superadmin/mail")
 */
class SuperadminMailController extends MailTemplateController
{

    protected function getInstance(): Instance
    { return $this->directory; }


    protected function listQuery(): QueryBuilder
    {
        return $this->repository
            ->createQueryBuilder('e')
            ->where('e.instance = :instance')
            ->setParameter('instance', $this->getDirectory()->getId());
    }


    /**
     * Lists all Templates Mail.
     *
     * @Route("/", name="superadmin_mails")
     */
    public function index(): Response
    { return $this->baseInstanceIndex(type: MailTemplateFilterType::class); }


    /**
     * Displays a form to create a new mail template.
     *
     * @Route("/new", name="superadmin_mails_new")
     */
    public function new(): Response
    { return $this->baseInstanceNew(); }


    /**
     * Displays a form to edit an existing mail template.
     *
     * @Route("/{id}/edit", name="superadmin_mails_edit")
     *
     * @param string $id The mail template ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit(string $id): Response
    { return $this->baseInstanceEdit($id); }


    /**
     * Creates a new Mail Entity.
     *
     * @Route("/create", name="superadmin_mails_create", methods={"POST"})
     */
    public function create(): Response
    { return $this->baseInstanceCreate(route: 'superadmin_mails'); }


    /**
     * Edits an existing Mail TEmplate.
     *
     * @Route("/{id}/update", name="superadmin_mails_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update(string $id): Response
    { return $this->baseInstanceUpdate($id, 'superadmin_mails'); }


    /**
     * Change state an existing Mail TEmplate.
     *
     * @Route("/{id}/change_state", name="superadmin_mails_change_state")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function changeState(string $id): Response
    {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error('entity_not_found');

        $entity->setEnabled(!$entity->getEnabled());

        $this->persistEntity($entity);

        $this->addEntityFlash(
            'success',
            'The %entity% was successfully '
            . (($entity->getEnabled()) ? 'enabled' : 'disabled')
        );

        return $this->redirect($this->generateUrl('superadmin_mails'));
    }
}
