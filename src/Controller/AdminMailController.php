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
use Celsius3\Entity\MailTemplate;
use Celsius3\Form\Type\MailTemplateType;
use Celsius3\Form\Type\Filter\MailTemplateFilterType;
use Celsius3\Validator\Constraints as CelsiusAssert;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * Order controller.
 *
 * @Route("/admin/mail")
 */
class AdminMailController extends BaseInstanceDependentController
{

    private AuthorizationChecker $authorizationChecker;

    private ValidatorInterface $validator;

    public function __construct(
        AuthorizationChecker $authorizationChecker,
        ValidatorInterface $validator,
        ...$args
    ) {
        parent::__construct(... $args);
        $this->authorizationChecker = $authorizationChecker;
        $this->validator = $validator;
    }

    protected final function getEntity(): string
    { return MailTemplate::class; }

    protected final function getType(): string
    { return MailTemplateType::class; }


    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        ];
    }


    /**
     * Lists all Templates Mail.
     *
     * @Route("/", name="admin_mails")
     */
    public function index(): Response
    {
        return $this->baseInstanceIndex(filter_form: 
            $this->createForm(MailTemplateFilterType::class)
        );
    }


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
            formOptions: [
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
    {
        return $this->baseInstanceCreate();
    }


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
