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

use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\Exception\Exception;
use Celsius3\Form\Type\BaseUserType;
use Celsius3\Form\Type\Filter\BaseUserFilterType;
use Celsius3\Form\Type\UserTransformType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * BibliotecarioBaseUser controller.
 *
 * @Route("/bibliotecario/user")
 */
class BibliotecarioBaseUserController extends BaseUserController
{
    /**
     * Lists all BaseUser entities.
     *
     * @Route("/", name="bibliotecario_user" ,options={"expose"=true})
     */
    public function index()
    {
        $parameters = $this->baseIndex('BaseUser', $this->createForm(BaseUserFilterType::class, null, [
            'instance' => $this->getInstance(),
        ]));

        return $this->render('BibliotecarioBaseUser/index.html.twig', $parameters);
    }

    /**
     * Shows the data of a user.
     *
     * @Route("/{id}/show", name="bibliotecario_user_show", options={"expose"=true})
     */
    public function show($id)
    {
        $entity = $this->findQuery('BaseUser', $id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.user');
        }

        $messages = $this->get('fos_message.thread_manager')
            ->getParticipantSentThreadsQueryBuilder($entity)
            ->getQuery()->getResult();

        $parameters = [
            'element' => $entity,
            'messages' => $messages,
            'resultsPerPage' => $this->getResultsPerPage(),
        ];

        return $this->render('BibliotecarioBaseUser/show.html.twig', $parameters);
    }

    /**
     * Displays a form to create a new BaseUser entity.
     *
     * @Route("/new", name="bibliotecario_user_new")
     */
    public function new()
    {
        $parameters = $this->baseNew('BaseUser', new BaseUser(), BaseUserType::class, []);

        return $this->render('BibliotecarioBaseUser/new.html.twig', $parameters);
    }

    /**
     * Creates a new BaseUser entity.
     *
     * @Route("/create", name="bibliotecario_user_create")
     * @Method("post")
     */
    public function create(Request $request)
    {
        return $this->baseUserCreate($request, 'BibliotecarioBaseUser/new.html.twig');
    }

    /**
     * Displays a form to edit an existing BaseUser entity.
     *
     * @Route("/{id}/edit", name="bibliotecario_user_edit", options={"expose"=true})
     *
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function edit($id)
    {
        $parameters = $this->baseEdit('BaseUser', $id, BaseUserType::class, ['editing' => true]);

        return $this->render('BibliotecarioBaseUser/edit.html.twig', $parameters);
    }

    /**
     * Edits an existing BaseUser entity.
     *
     * @Route("/{id}/update", name="bibliotecario_user_update")
     * @Method("post")
     *
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function update($id, Request $request)
    {
        $entity = $this->findQuery('BaseUser', $id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.user');
        }

        $editForm = $this->createForm(BaseUserType::class, $entity, [
            'editing' => true,
        ]);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('celsius3_core.custom_field_helper')->processCustomUserFields($this->getInstance(), $editForm, $entity);

            $this->get('session')
                ->getFlashBag()
                ->add('success', 'The BaseUser was successfully edited.');

            return $this->redirect($this->generateUrl('admin_user_edit', [
                'id' => $id,
            ]));
        }

        $this->get('session')
            ->getFlashBag()
            ->add('error', 'There were errors editing the BaseUser.');

        $parameters = [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ];

        return $this->render('Admin/BaseUser/edit.html.twig', $parameters);
    }

    /**
     * Displays a form to transform an existing BaseUser entity.
     *
     * @Route("/{id}/transform", name="bibliotecario_user_transform")
     *
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function transform($id, Request $request)
    {
        $entity = $this->findQuery('BaseUser', $id);

        if ($request->getMethod() === 'POST') {
            return $this->baseDoTransform($id, UserTransformType::class, [
                'instance' => $this->getInstance(),
                'user' => $entity,
            ], 'admin_user');
        }

        $response = $this->baseTransform($id, UserTransformType::class, [
            'instance' => $this->getInstance(),
            'user' => $entity,
        ]);

        if ($response instanceof RedirectResponse) {
            return $response;
        }

        return $this->render('BibliotecarioBaseUser/transform.html.twig', $response);
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
    public function enable($id)
    {
        return $this->baseEnable($id);
    }

    /**
     * Batch actions.
     *
     * @Route("/batch", name="bibliotecario_user_batch")
     *
     * @return array
     */
    public function batch()
    {
        return $this->baseBatch();
    }

    /**
     * Unifies a group of Journal entities.
     *
     * @Route("/batch/doUnion", name="bibliotecario_user_doUnion")
     * @Method("post")
     *
     * @return array
     */
    public function doUnion(Request $request)
    {
        $element_ids = $request->request->get('element');
        $main_id = $request->request->get('main');

        return $this->baseDoUnion('BaseUser', $element_ids, $main_id, 'admin_user', false);
    }

    protected function getSortDefaults()
    {
        return [
            'defaultSortFieldName' => 'e.surname',
            'defaultSortDirection' => 'asc',
        ];
    }

    protected function batchEnable($element_ids)
    {
        return $this->baseBatchEnable($element_ids);
    }

    protected function batchUnion($element_ids)
    {
        return $this->render('Admin/BaseUser/batchUnion.html.twig', $this->baseUnion('BaseUser', $element_ids));
    }

    protected function getUserListRoute()
    {
        return 'bibliotecario_user';
    }
}
