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
use Celsius3\Validator\Constraints as CelsiusAssert;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Celsius3\Controller\Base\MailController;

use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Order controller.
 *
 * @Route("/admin/mail")
 */
class AdminMailController extends MailController
{

    private AuthorizationCheckerInterface $authorizationChecker;

    private ValidatorInterface $validator;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        ValidatorInterface $validator,
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

        $this->authorizationChecker = $authorizationChecker;
        $this->validator = $validator;
    }


    /**
     * Lists all Templates Mail.
     *
     * @Route("/", name="admin_mails")
     */
    public function index(): Response
    { return $this->baseInstanceIndex(); }


    /**
     * Displays a form to create a new mail template.
     *
     * @Route("/new", name="admin_mails_new")
     */
    public function new(): Response
    {
        return $this->baseInstanceNew(
            options: [
                'super_admin' => $this->authorizationChecker
                    ->isGranted('ROLE_SUPER_ADMIN')
            ]
        );
    }


    /**
     * Displays a form to edit an existing mail template.
     *
     * @Route("/{id}/edit", name="admin_mails_edit")
     *
     * @param string $id The mail template ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit(string $id): RedirectResponse|Response
    {
        //Se debe determinar si se utilizara admin_mails_edit o admin_mails_create, dependiendo
        //si la plantilla le pertenece al directorio o a la instancia.
        $entity = $this->findQuery($id);

        if ($entity->instance !== $this->directory) {
            $route = $this->generateUrl('admin_mails_update', ['id' => $id]);
        } else {
            $result = $this->repository
                ->findBy([
                    'code' => $entity->getCode(),
                    'instance' => $this->instance
                ]);

            if (count($result) > 0) {
                return $this->redirectToRoute('admin_mails');
            }

            $route = $this->generateUrl('admin_mails_create');
        }

        $form = $this->createForm(
            options: [
                'instance' => $this->getInstance(),
                'code' => $entity->getCode(),
                'action' => $route,
                'super_admin' => $this->authorizationChecker
                    ->isGranted('ROLE_SUPER_ADMIN')
            ]
        );

        return $this->render(
            (string) $this->templatePrefix . 'edit.html.twig',
            [
                'entity' => $entity,
                'edit_form' => $form->createView(),
                'route' => $route,
            ]
        );
    }


    /**
     * Creates a new Mail Entity.
     *
     * @Route("/create", name="admin_mails_create", methods={"POST"})
     *
     */
    public function create(): RedirectResponse|Response
    { return $this->baseInstanceCreate(); }


    /**
     * Edits an existing Mail TEmplate.
     *
     * @Route("/{id}/update", name="admin_mails_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update(string $id): RedirectResponse|Response
    {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error('entity_not_found');

        $editForm = $this->createForm(data: $entity);

        $request = $this->requestStack->getCurrentRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $errorList = $this->validator->validate(
                $entity->getText(),
                new CelsiusAssert\MailTemplate()
            );

            if (0 === count($errorList)) {
                try {
                    $this->persistEntity($entity);

                    $this->addEntityFlash(
                        'success', 'The %entity% was successfully edited.'
                    );

                    return $this->redirect(
                        $this->generateUrl(
                            'admin_mails_edit',
                            [ 'id' => $id ]
                        )
                    );
                } catch (UniqueConstraintViolationException $e) {
                    $this->addEntityFlash(
                        'error', 'The %entity% already exists.'
                    );
                }
            } else {
                $editForm->get('text')->addError(new FormError('error.invalid.mail_template'));
            }
        }

        $this->addEntityFlash(
            'error', 'There were errors editing the %entity%.'
        );
    
        return $this->render('Admin/Mail/edit.html.twig', [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ]);
    }


    /**
     * Change state an existing Mail TEmplate.
     *
     * @Route("/{id}/change_state", name="admin_mails_change_state")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function changeState(string $id): Response
    {
        $entity = $this->findQuery($id);

        if (!$entity || $entity->getInstance()->getId() === $this->directory->getId())
            $this->error('entity_not_found');

        $entity->setEnabled(!$entity->getEnabled());

        $this->persistEntity($entity);

        $this->addFlash(
            'success', 'The Template was successfully '.(
                ($entity->getEnabled()) ? 'enabled' : 'disabled'
            )
        );

        return $this->redirect($this->generateUrl('admin_mails'));
    }
}
