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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\ContactController;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Contact controller.
 * @Route("/superadmin/contact")
 */
class HtmlSuperadminContactController extends ContactController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->setInstanceDependent(false);
        $this->setInstance($this->directory);
    }


    /**
     * Lists all Contact entities.
     * @Route("/", name="superadmin_contact")
     */
    public function htmlIndex(): Response
    {
        $pagination = $this->paginate();

        $deleteForms = [];
        foreach ($pagination as $entity) {
            $deleteForms[$entity->getId()] = $this
                ->createDeleteForm($entity->getId())
                ->createView();
        }

        return $this->htmlRenderer->render(
            'index',
            [
                'pagination' => $pagination,
                'deleteForms' => $deleteForms
            ]
        );
    }


    /**
     * Finds and displays a Contact entity.
     * @Route("/{id}/show", name="superadmin_contact_show")
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlShow(string $id): Response
    {
        return $this->htmlRenderer->render(
            'show',
            $this->show($id)
        );
    }


    /**
     * Displays a form to create a new Contact entity.
     * @Route("/new", name="superadmin_contact_new")
     */
    public function htmlNew(): Response
    {
        return $this->htmlRenderer->render(
            'new',
            $this->new()
        );
    }


    /**
     * Creates a new Contact entity.
     * @Route("/create", name="superadmin_contact_create", methods={"POST"})
     */
    public function htmlCreate(): Response
    {
        return $this->htmlRenderer->render(
            'new',
            $this->create()
        );
    }


    protected function editFormOptions(
        $entity,
        ?string $type = null,
        ?string $redirectRoute = null,
        ?bool $isInstanceDependent,
        ?array $formExtraOptions = []
    ): array {
        return [
            'owning_instance' => $entity->getOwningInstance(),
            'user' => $entity->getUser(),
            ... $formExtraOptions
        ];
    }


    /**
     * Displays a form to edit an existing Contact entity.
     * @Route("/{id}/edit", name="superadmin_contact_edit")
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlEdit(string $id): Response
    {
        return $this->htmlRenderer->render(
            'edit',
            $this->edit($id)
        );
    }


    protected function updateFormOptions(
        $entity,
        ?string $type = null,
        ?string $redirectRoute = null,
        ?bool $isInstanceDependent,
        ?array $formExtraOptions = []
    ): array {
        return [
            'owning_instance' => $entity->getOwningInstance(),
            'user' => $entity->getUser(),
            ... $formExtraOptions
        ];
    }


    /**
     * Edits an existing Contact entity.
     * @Route("/{id}/update", name="superadmin_contact_update", methods={"POST"})
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlUpdate(string $id): Response
    {
        return $this->htmlRenderer->render(
            'edit',
            $this->update($id)
        );
    }


    /**
     * Deletes a Contact entity.
     * @Route("/{id}/delete", name="superadmin_contact_delete", methods={"POST"})
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlDelete(string $id): RedirectResponse
    {
        return $this->htmlRenderer->render(
            'delete',
            $this->delete($id)
        );
    }

}
