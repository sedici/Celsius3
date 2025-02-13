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

use Celsius3\Form\Type\UserTransformType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Celsius3\Controller\Base\UserController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * BibliotecarioBaseUser controller.
 * @Route("/bibliotecario/user")
 */
class HtmlBibliotecarioBaseUserController extends UserController
{

    public function initialize(): void
    {
        parent::initialize();

        $this->htmlRenderer->setTemplatePrefix('BibliotecarioBaseUser/');
    }


    /**
     * Lists all BaseUser entities.
     * @Route("/", name="bibliotecario_user" ,options={"expose"=true})
     */
    public function htmlIndex(): Response
    {
        return $this->htmlRenderer->render(
            'index',
            $this->index()
        );
    }


    /**
     * Shows the data of a user.
     * @Route("/{id}/show", name="bibliotecario_user_show", options={"expose"=true})
     */
    public function htmlShow(string $id): Response
    {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error('entity_not_found');

        $messages = $this->threadManager
            ->getParticipantSentThreadsQueryBuilder($entity)
            ->getQuery()->getResult();
        
        return $this->htmlRenderer->render(
            'show',
            [
                'element' => $entity,
                'messages' => $messages,
                'resultsPerPage' => $this->getResultsPerPage(),
            ]
        );
    }


    /**
     * Displays a form to create a new BaseUser entity.
     * @Route("/new", name="bibliotecario_user_new")
     */
    public function htmlNew(): Response
    {
        return $this->htmlRenderer->render(
            'new',
            $this->new(formOptions: ['validation_groups' => 'Registration'])
        );
    }


    /**
     * Creates a new BaseUser entity.
     * @Route("/create", name="bibliotecario_user_create", methods={"POST"})
     */
    public function htmlCreate(): RedirectResponse|Response
    {
        return $this->htmlRenderer->render(
            'create',
            $this->create(redirectRoute: 'admin_user_new')
        );
    }

    /**
     * Displays a form to edit an existing BaseUser entity.
     * @Route("/{id}/edit", name="bibliotecario_user_edit", options={"expose"=true})
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlEdit(string $id): Response
    {
        return $this->htmlRenderer->render(
            'edit',
            $this->edit(
                $id, formOptions: [ 'editing' => true ]
            )
        );
    }


    protected function onValidUpdateForm(
        $entity,
        FormInterface $form,
        array $formOptions,
        string $redirectRoute,
        Request $request
    ): void {
        $this->persistEntity($entity);

        $this->customFieldHelper->processCustomUserFields(
            $this->instance, $form, $entity
        );
    }


    /**
     * Edits an existing BaseUser entity.
     * @Route("/{id}/update", name="bibliotecario_user_update", methods={"POST"})
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlUpdate(
        string $id
    ): RedirectResponse|Response {
        return $this->htmlRenderer->render(
            'update',
            $this->update(
                $id, 'admin_user_edit',
                formOptions: [ 'editing' => true ]
            )
        );
    }


    /**
     * Displays a form to transform an existing BaseUser entity.
     * @Route("/{id}/transform", name="bibliotecario_user_transform")
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function transform(string $id): array|RedirectResponse|Response
    {
        $entity = $this->findQuery($id);

        $request = $this->requestStack->getCurrentRequest();

        if ($request->getMethod() === 'POST') {
            return $this->baseDoTransform(
                $id,
                UserTransformType::class,
                [
                    'instance' => $this->instance,
                    'user' => $entity,
                ],
                'admin_user'
            );
        }

        $response = $this->baseTransform(
            $id,
            UserTransformType::class,
            [
                'instance' => $this->instance,
                'user' => $entity,
            ]
        );

        if ($response instanceof RedirectResponse) {
            return $response;
        }

        return $this->htmlRenderer->render(
            'transform',
            $response
        );
    }


    /**
     * Enables a BaseUser entity.
     * @Route("/{id}/enable", name="bibliotecario_user_enable", options={"expose"=true})
     * @param string $id The entity ID
     * @return array
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function enable(string $id): RedirectResponse
    { return $this->baseEnable($id); }


    /**
     * Batch actions.
     * @Route("/batch", name="bibliotecario_user_batch")
     * @return array
     */
    public function batch()
    { return $this->baseBatch(); }


    /**
     * Unifies a group of Journal entities.
     * @Route("/batch/doUnion", name="bibliotecario_user_doUnion", methods={"POST"})
     */
    public function doUnion(): RedirectResponse
    {
        $request = $this->requestStack->getCurrentRequest();
        $element_ids = $request->get('element');
        $main_id = $request->get('main');

        $this->baseDoUnion(
            $element_ids,
            $main_id,
            false
        );

        return $this->redirectToRoute('admin_user');
    }


    protected function batchEnable($element_ids): RedirectResponse
    { return $this->baseBatchEnable($element_ids); }


    // REVISAR
    protected function batchUnion(array $element_ids): Response
    {
        return $this->htmlRenderer->render(
            'batchUnion',
            $this->baseUnion($element_ids)
        );
    }
}
