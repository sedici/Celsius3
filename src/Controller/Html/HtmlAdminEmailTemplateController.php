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

use Celsius3\Controller\Base\EmailTemplateController;
use Celsius3\Validator\Constraints as CelsiusAssert;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


#[
    Route('/admin/email_template'),
    IsGranted('ROLE_ADMIN')
]
class HtmlAdminEmailTemplateController extends EmailTemplateController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->htmlRenderer->setTemplatePrefix('Admin/EmailTemplate/');
    }


    #[Route('/', name: 'admin_emailtemplate')]
    public function htmlIndex(): Response
    { return $this->htmlRenderer->render('index', $this->index(hasFilterForm:false)); }


    #[Route('/new', name: 'admin_emailtemplate_new')]
    public function htmlNew(): Response
    {
        return $this->htmlRenderer->render(
            'new',
            $this->new(formOptions: [
                'super_admin' => $this->security
                    ->isGranted('ROLE_SUPER_ADMIN')
            ])
        );
    }


    /**
     * @param string $id The mail template ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    #[Route('/{id}/edit', name: 'admin_emailtemplate_edit')]
    public function htmlEdit(string $id): RedirectResponse|Response
    {
        //Se debe determinar si se utilizara admin_emailtemplate_edit o admin_emailtemplate_create, dependiendo
        //si la plantilla le pertenece al directorio o a la instancia.
        $entity = $this->findQuery($id);

        if ($entity->instance !== $this->directory) {
            $route = $this->generateUrl('admin_emailtemplate_update', ['id' => $id]);
        } else {
            $result = $this->repository
                ->findBy([
                    'code' => $entity->getCode(),
                    'instance' => $this->instance
                ]);

            if (count($result) > 0) {
                return $this->redirectToRoute('admin_emailtemplate');
            }

            $route = $this->generateUrl('admin_emailtemplate_create');
        }

        $form = $this->createForm(
            options: [
                'instance' => $this->instance,
                'code' => $entity->getCode(),
                'action' => $route,
                'super_admin' => $this->security
                    ->isGranted('ROLE_SUPER_ADMIN')
            ]
        );

        return $this->htmlRenderer->render(
            'edit',
            [
                'entity' => $entity,
                'edit_form' => $form->createView(),
                'route' => $route,
            ]
        );
    }


    #[Route('/create', name: 'admin_emailtemplate_create', methods: ['POST'])]
    public function htmlCreate(): RedirectResponse|Response
    {
        return $this->htmlRenderer->render(
            'create',
            $this->create()
        );
    }


    /**
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    #[Route('/{id}/update', name: 'admin_emailtemplate_update', methods: ['POST'])]
    public function htmlUpdate(string $id): RedirectResponse|Response
    {
        $entity = $this->findQuery($id);
        if (!$entity) $this->error('entity_not_found');

        $editForm = $this->createForm(data: $entity);
        $request = $this->requestStack->getCurrentRequest();
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $errorList = $this->validator->validate(
                $entity->getText(),
                new CelsiusAssert\EmailTemplate()
            );

            if (0 === count($errorList)) {
                try {
                    $this->persistEntity($entity);

                    $this->addEntityFlash(
                        'success', 'The %entity% was successfully edited.'
                    );

                    return $this->redirect(
                        $this->generateUrl(
                            'admin_emailtemplate_edit',
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
    
        return $this->htmlRenderer->render(
            'edit',
            [
                'entity' => $entity,
                'edit_form' => $editForm->createView()
            ]
        );
    }


    /**
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    #[Route('/{id}/change_state', name: 'admin_emailtemplate_changestate')]
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

        return $this->redirectToRoute('admin_emailtemplate');
    }
}
