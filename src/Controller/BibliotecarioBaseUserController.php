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

use Celsius3\EntityManager\ThreadManager;
use Celsius3\Form\Type\Filter\BaseUserFilterType;
use Celsius3\Form\Type\UserTransformType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * BibliotecarioBaseUser controller.
 *
 * @Route("/bibliotecario/user")
 */
class BibliotecarioBaseUserController extends BaseUserController
{

    protected ThreadManager $threadManager;

    public function __construct(
        ThreadManager $threadManager,
        ...$args
    ) {
        parent::__construct(... $args);
        $this->threadManager = $threadManager;
    }


    protected final function getTemplatePrefix(): string
    { return 'BibliotecarioBaseUser/'; }


    /**
     * Lists all BaseUser entities.
     *
     * @Route("/", name="bibliotecario_user" ,options={"expose"=true})
     */
    public function index(): Response
    { return $this->baseInstanceIndex(type: BaseUserFilterType::class); }


    /**
     * Shows the data of a user.
     *
     * @Route("/{id}/show", name="bibliotecario_user_show", options={"expose"=true})
     */
    public function show(string $id): Response
    {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error('entity_not_found');

        $messages = $this->threadManager
            ->getParticipantSentThreadsQueryBuilder($entity)
            ->getQuery()->getResult();

        return $this->render(
            (string) $this->templatePrefix . 'show.html.twig',
            [
                'element' => $entity,
                'messages' => $messages,
                'resultsPerPage' => $this->getResultsPerPage(),
            ]
        );
    }


    /**
     * Displays a form to create a new BaseUser entity.
     *
     * @Route("/new", name="bibliotecario_user_new")
     */
    public function new(): Response
    { return $this->baseInstanceNew(); }


    /**
     * Creates a new BaseUser entity.
     *
     * @Route("/create", name="bibliotecario_user_create", methods={"POST"})
     */
    public function create(): RedirectResponse|Response
    { return $this->baseInstanceCreate(); }


    /**
     * Displays a form to edit an existing BaseUser entity.
     *
     * @Route("/{id}/edit", name="bibliotecario_user_edit", options={"expose"=true})
     *
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function edit(string $id): Response
    {
        return $this->baseInstanceEdit(
            $id, options: [ 'editing' => true ]
        );
    }


    /**
     * Edits an existing BaseUser entity.
     *
     * @Route("/{id}/update", name="bibliotecario_user_update", methods={"POST"})
     *
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function update(string $id): RedirectResponse|Response
    {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error('entity_not_found');

        $editForm = $this->createForm(formOptions: [ 'editing' => true ]);

        $request = $this->requestStack->getCurrentRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->persistEntity($entity);

            $this->customFieldHelper->processCustomUserFields(
                $this->getInstance(), $editForm, $entity
            );

            $this->addEntityFlash(
                'success', 'The %entity% was successfully edited.'
            );

            return $this->redirect(
                $this->generateUrl(
                    'admin_user_edit',
                    [ 'id' => $id ]
                )
            );
        }

        $this->addEntityFlash(
            'error', 'There were errors editing the %entity%.'
        );

        $parameters = [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ];

        return $this->render(
            'Admin/BaseUser/edit.html.twig',
            $parameters
        );
    }


    /**
     * Displays a form to transform an existing BaseUser entity.
     *
     * @Route("/{id}/transform", name="bibliotecario_user_transform")
     *
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

        return $this->render(
            (string) $this->templatePrefix . 'transform.html.twig',
            $response
        );
    }


    /**
     * Enables a BaseUser entity.
     *
     * @Route("/{id}/enable", name="bibliotecario_user_enable", options={"expose"=true})
     *
     * @param string $id
     *                   The entity ID
     *
     * @return array
     *
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function enable(string $id): RedirectResponse
    { return $this->baseEnable($id); }


    /**
     * Batch actions.
     *
     * @Route("/batch", name="bibliotecario_user_batch")
     *
     * @return array
     */
    public function batch()
    { return $this->baseBatch(); }


    /**
     * Unifies a group of Journal entities.
     *
     * @Route("/batch/doUnion", name="bibliotecario_user_doUnion", methods={"POST"})
     */
    public function doUnion(): RedirectResponse
    {
        $request = $this->requestStack->getCurrentRequest();
        $element_ids = $request->get('element');
        $main_id = $request->get('main');

        return $this->baseDoUnion(
            $element_ids,
            $main_id,
            'admin_user',
            false
        );
    }


    protected function batchEnable($element_ids): RedirectResponse
    { return $this->baseBatchEnable($element_ids); }


    protected function batchUnion(array $element_ids): Response
    {
        return $this->render(
            'Admin/BaseUser/batchUnion.html.twig',
            $this->baseUnion($element_ids)
        );
    }
}
