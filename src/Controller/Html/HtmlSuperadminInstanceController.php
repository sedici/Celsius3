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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Celsius3\Controller\Base\InstanceController;
use Celsius3\Entity\Instance;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Instance controller.
 * @Route("/superadmin/instance")
 */
class HtmlSuperadminInstanceController extends InstanceController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->setEntity(Instance::class);
        $this->setInstanceDependent(false);
        $this->htmlRenderer->setTemplatePrefix('Superadmin/Instance/');
    }


    /**
     * Lists all Instance entities.
     * @Route("/", name="superadmin_instance")
     */
    public function htmlIndex(): Response
    { return $this->htmlRenderer->render('index', $this->index()); }


    /**
     * Displays a form to create a new Instance entity.
     * @Route("/new", name="superadmin_instance_new")
     */
    public function htmlNew(): Response
    { return $this->htmlRenderer->render(
        'new', $this->new(formOptions: ['institution_select' => true]));
    }


    protected function createFormOptions(
        string|null $type = null,
        string|null $redirectRoute = null,
        array|null $formExtraOptions = []
    ): array {
        return array_merge(
            parent::createFormOptions(
                $type, $redirectRoute, $formExtraOptions
            ),
            ['institution_select' => true]
        );
    }


    protected function onValidCreateForm(
        $entity,
        FormInterface $form,
        array $formOptions,
        string $redirectRoute,
        Request $request
    ): void {
        $institution = $this->findQuery(
            $request->get('instance')['institution']
        );

        if ($institution === null) $this->error(Exception::ENTITY_NOT_FOUND);

        $this->entityManager->wrapInTransaction(
            function () use ($entity, $institution) {
                $this->persistEntity($entity);
                
                $institution->setCelsiusInstance($entity);
                $this->persistEntity($institution);
            }
        );

        $this->fileManager->createFilesDirectory($entity->getUrl());
    }


    /**
     * Creates a new Instance entity.
     * @Route("/create", name="superadmin_instance_create", methods={"POST"})
     */
    public function htmlCreate(): RedirectResponse|Response
    { return $this->htmlRenderer->render('create', $this->create()); }


    /**
     * Displays a form to edit an existing Instance entity.
     * @Route("/{id}/edit", name="superadmin_instance_edit")
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlEdit(string $id): Response
    { return $this->htmlRenderer->render('edit', $this->edit($id)); }


    /**
     * Edits an existing Instance entity.
     * @Route("/{id}/update", name="superadmin_instance_update", methods={"POST"})
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlUpdate(string $id): RedirectResponse|Response
    { return $this->htmlRenderer->render('edit', $this->update($id)); }


    /**
     * Switches the enabled flag of a Instance entity.
     * @Route("/{id}/switch", name="superadmin_instance_switch")
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function switch(string $id): RedirectResponse
    {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error(Exception::ENTITY_NOT_FOUND);

        $entity->setEnabled(!$entity->getEnabled());

        $this->persistEntity($entity);

        $this->addFlash(
            'success',
            'The Instance was successfully '.(
                ($entity->getEnabled())
                    ? 'enabled'
                    : 'disabled'
            )
        );

        return $this->redirect(
            $this->generateUrl(
                $entity->isCurrent()
                    ? 'superadmin_instance'
                    : 'superadmin_instance_legacy'
            )
        );
    }


    /**
     * Switches the enabled flag of a Instance entity.
     * @Route("/{id}/invisible", name="superadmin_instance_invisible")
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function invisible(string $id): RedirectResponse
    {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error(Exception::ENTITY_NOT_FOUND);

        $entity->setInvisible(!$entity->getInvisible());

        $this->persistEntity($entity);

        $this->addFlash(
            'success',
            'The Instance was successfully '.(
                ($entity->getInvisible())
                    ? 'hidden'
                    : 'show'
                )
        );

        return $this->redirect(
            $this->generateUrl(
                $entity->isCurrent()
                    ? 'superadmin_instance'
                    : 'superadmin_instance_legacy'
            )
        );
    }


    /**
     * Displays a form to configure the Directory.
     * @Route("/directory/configure", name="superadmin_directory_configure")
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function configureDirectory(): Response
    {
        return $this->htmlRenderer->render(
            'configure',
            $this->baseConfigure(
                '' . $this->instanceManager->getDirectory()->getId()
            )
        );
    }


    /**
     * Displays a form to configure an existing Instance.
     * @Route("/{id}/configure", name="superadmin_instance_configure")
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function configure(string $id): Response
    {
        return $this->htmlRenderer->render(
            'configure',
            $this->baseConfigure($id)
        );
    }


    /**
     * Edits the existing Instance configuration.
     * @Route("/{id}/update_configuration", name="superadmin_instance_update_configuration", methods={"POST"})
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function configureUpdate(string $id): Response
    {
        return $this->htmlRenderer->render(
            'configure',
            $this->baseConfigureUpdate(
                $id,
                'superadmin_instance'
            )
        );
    }


    /**
     * Redirects to the administration of an Instance entity.
     * @Route("/{id}/admin", name="superadmin_instance_admin")
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function admin(string $id): RedirectResponse
    {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error(Exception::ENTITY_NOT_FOUND);

        $this->session->set('instance_id', $entity->getId());
        $this->session->set('instance_url', $entity->getUrl());
        $this->session->set('instance_host', $entity->getHost());

        $this->session->set('admin_instance', $this->instanceHelper->getSessionOrUrlInstance());

        return $this->redirectToRoute('administration');
    }
}
