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
use Celsius3\Entity\Institution;
use Celsius3\Form\Type\Filter\InstanceFilterType;
use Celsius3\Manager\FileManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Helper\MailerHelper;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Instance controller.
 *
 * @Route("/superadmin/instance")
 */
class SuperadminInstanceController extends InstanceController
{

    private SessionInterface $session;
    protected FileManager $fileManager;

    protected function __construct(
        SessionInterface $session,
        FileManager $fileManager,
        MailerHelper $mailerHelper,
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
        parent::__construct(
            $mailerHelper,
            $instanceManager,
            $entityManager,
            $paginator,
            $configurationHelper,
            $translator,
            $managerRegistry,
            $requestStack,
            $unionManager,
            $userManager,
            $filterManager,
            $instanceHelper
        );
        $this->fileManager = $fileManager;
        $this->session = $session;
    }


    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        ];
    }


    /**
     * Lists all Instance entities.
     *
     * @Route("/", name="superadmin_instance")
     */
    public function index(): Response
    { return $this->baseIndex(type: InstanceFilterType::class); }


    /**
     * Displays a form to create a new Instance entity.
     *
     * @Route("/new", name="superadmin_instance_new")
     */
    public function new(): Response
    { return $this->baseNew(formOptions: ['institution_select' => true]); }


    /**
     * Creates a new Instance entity.
     *
     * @Route("/create", name="superadmin_instance_create", methods={"POST"})
     */
    public function create(): RedirectResponse|Response
    {
        $request = $this->requestStack->getCurrentRequest();

        $instance = new Instance();
        $form = $this->createForm(
            $this->entityClassName,
            $instance,
            [
                'institution_select' => true
            ]
        );

        $form->handleRequest($request);
        if ($form->isValid()) {
            try {
                $institution = $this->objectManager
                    ->getRepository(Institution::class)
                    ->find($request->get('instance')['institution']);
                
                if ($institution === null) $this->error(
                    'entity_not_found',
                    msg: 'Not found institution'
                );

                $this->entityManager->wrapInTransaction(
                    function () use ($instance, $institution) {
                        $this->persistEntity($instance);
                        
                        $institution->setCelsiusInstance($instance);
                        $this->persistEntity($institution);
                    }
                );

                $this->fileManager
                    ->createFilesDirectory($instance->getUrl());

                $this->addEntityFlash(
                    'success', 'The %entity% was successfully created.'
                );

                return $this->redirect(
                    $this->generateUrl('superadmin_instance')
                );
            } catch (UniqueConstraintViolationException $e) {
                $this->addEntityFlash('error', 'The %entity% already exists.');
            } catch (\Exception $e) {
                $this->addEntityFlash('error', 'Error to persist %entity%.');
            }
        }

        $this->addEntityFlash(
            'error', 'There were errors creating the %entity%.'
        );

        return $this->render(
            (string) $this->templatePrefix . 'new.html.twig',
            [
                'entity' => $instance,
                'form' => $form->createView(),
            ]
        );
    }


    /**
     * Displays a form to edit an existing Instance entity.
     *
     * @Route("/{id}/edit", name="superadmin_instance_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit(string $id): Response
    { return $this->baseEdit($id); }


    /**
     * Edits an existing Instance entity.
     *
     * @Route("/{id}/update", name="superadmin_instance_update", methods={"POST"})
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update(string $id): RedirectResponse|Response
    { return $this->baseUpdate($id, 'superadmin_instance'); }


    /**
     * Switches the enabled flag of a Instance entity.
     *
     * @Route("/{id}/switch", name="superadmin_instance_switch")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function switch(string $id): RedirectResponse
    {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error('entity_not_found');

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
     *
     * @Route("/{id}/invisible", name="superadmin_instance_invisible")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function invisible(string $id): RedirectResponse
    {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error('entity_not_found');

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
     *
     * @Route("/directory/configure", name="superadmin_directory_configure")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function configureDirectory(): Response
    {
        return $this->render(
            (string) $this->templatePrefix . 'configure.html.twig',
            $this->baseConfigure(
                '' . $this->instanceManager->getDirectory()->getId()
            )
        );
    }


    /**
     * Displays a form to configure an existing Instance.
     *
     * @Route("/{id}/configure", name="superadmin_instance_configure")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function configure(string $id): Response
    {
        return $this->render(
            (string) $this->templatePrefix . 'configure.html.twig',
            $this->baseConfigure($id)
        );
    }


    /**
     * Edits the existing Instance configuration.
     *
     * @Route("/{id}/update_configuration", name="superadmin_instance_update_configuration", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function configureUpdate(string $id): Response
    {
        return $this->render(
            (string) $this->templatePrefix . 'configure.html.twig',
            $this->baseConfigureUpdate(
                $id,
                'superadmin_instance'
            )
        );
    }


    /**
     * Redirects to the administration of an Instance entity.
     *
     * @Route("/{id}/admin", name="superadmin_instance_admin")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function admin(string $id): RedirectResponse
    {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error('entity_not_found');

        $this->session->set('instance_id', $entity->getId());
        $this->session->set('instance_url', $entity->getUrl());
        $this->session->set('instance_host', $entity->getHost());

        $this->session->set('admin_instance', $this->instanceHelper->getSessionOrUrlInstance());

        return $this->redirect($this->generateUrl('administration'));
    }
}
