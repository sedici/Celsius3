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

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\EmailTemplateController;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


#[
    Route('/superadmin/email_template'),
    IsGranted('ROLE_SUPER_ADMIN')
]
class HtmlSuperadminEmailTemplateController extends EmailTemplateController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->setInstanceDependent(false);
        $this->htmlRenderer->setTemplatePrefix('Superadmin/EmailTemplate/');
    }


    public function listQuery(?bool $isInstanceDependet = null): QueryBuilder
    {
        return $this->repository
            ->createQueryBuilder('e')
            ->where('e.instance = :instance')
            ->setParameter('instance', $this->instance->getId());
    }


    /**
     * @Route("/", name="superadmin_emailtemplate")
     */
    public function htmlIndex(): Response
    { return $this->htmlRenderer->render('index', $this->index()); }


    /**
     * @Route("/new", name="superadmin_emailtemplate_new")
     */
    public function htmlNew(): Response
    { return $this->htmlRenderer->render('new', $this->new()); }


    /**
     * Displays a form to edit an existing mail template.
     * @Route("/{id}/edit", name="superadmin_emailtemplate_edit")
     * @param string $id The mail template ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlEdit(string $id): Response
    { return $this->htmlRenderer->render('edit', $this->edit($id)); }


    /**
     * Creates a new Mail Entity.
     * @Route("/create", name="superadmin_emailtemplate_create", methods={"POST"})
     */
    public function htmlCreate(): Response
    { return $this->htmlRenderer->render('new', $this->create()); }


    /**
     * Edits an existing Mail TEmplate.
     * @Route("/{id}/update", name="superadmin_emailtemplate_update", methods={"POST"})
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlUpdate(string $id): Response
    { return $this->htmlRenderer->render('edit', $this->update($id)); }


    /**
     * Change state an existing Mail TEmplate.
     * @Route("/{id}/change_state", name="superadmin_emailtemplate_changestate")
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

        return $this->redirectToRoute('superadmin_emailtemplate');
    }
}
