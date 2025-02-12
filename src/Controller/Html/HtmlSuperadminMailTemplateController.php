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

namespace Celsius3\Controller\Html;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\MailTemplateController;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * Order controller.
 * @Route("/superadmin/mail")
 */
class HtmlSuperadminMailTemplateController extends MailTemplateController
{

    public function listQuery(?bool $isInstanceDependet = null): QueryBuilder
    {
        return $this->repository
            ->createQueryBuilder('e')
            ->where('e.instance = :instance')
            ->setParameter('instance', $this->instance->getId());
    }


    /**
     * Lists all Templates Mail.
     * @Route("/", name="superadmin_mails")
     */
    public function htmlIndex(): Response
    {
        return $this->htmlRenderer->render(
            templateName: 'index',
            params: $this->index()
            // params: [
            //     'entities' => $this->getPagination(),
            //     'filterForm' => $this->getFilterForm()->createView(),
            // ]
        );
    }


    /**
     * Displays a form to create a new mail template.
     * @Route("/new", name="superadmin_mails_new")
     */
    public function htmlNew(): Response
    {
        return $this->htmlRenderer->render(
            templateName: 'new',
            params: $this->new()
        );
    }


    /**
     * Displays a form to edit an existing mail template.
     * @Route("/{id}/edit", name="superadmin_mails_edit")
     * @param string $id The mail template ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlEdit(string $id): Response
    {
        return $this->htmlRenderer->render(
            templateName: 'edit',
            params: $this->edit($id)
        );
    }


    /**
     * Creates a new Mail Entity.
     * @Route("/create", name="superadmin_mails_create", methods={"POST"})
     */
    public function htmlCreate(): Response
    {
        return $this->htmlRenderer->render(
            templateName: 'new',
            params: $this->create()
        );
    }


    /**
     * Edits an existing Mail TEmplate.
     * @Route("/{id}/update", name="superadmin_mails_update", methods={"POST"})
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlUpdate(string $id): Response
    {
        return $this->htmlRenderer->render(
            templateName: 'edit',
            params: $this->update($id)
        );
    }


    /**
     * Change state an existing Mail TEmplate.
     * @Route("/{id}/change_state", name="superadmin_mails_change_state")
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
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

        return $this->redirectToRoute('superadmin_mails');
    }
}
