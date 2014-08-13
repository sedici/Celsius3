<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Celsius3\CoreBundle\Document\BaseUser;
use Celsius3\CoreBundle\Form\Type\BaseUserType;
use Celsius3\CoreBundle\Form\Type\UserTransformType;
use Celsius3\CoreBundle\Filter\Type\BaseUserFilterType;
use Celsius3\CoreBundle\Manager\StateManager;

/**
 * BaseUser controller.
 *
 * @Route("/admin/user")
 */
class AdminBaseUserController extends BaseUserController
{

    /**
     * Lists all BaseUser documents.
     *
     * @Route("/", name="admin_user")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('BaseUser', $this->createForm(new BaseUserFilterType($this->getInstance())));
    }

    /**
     * Shows the data of a user
     *
     * @Route("/{id}/show", name="admin_user_show")
     * @Template()
     *
     * @return array
     */
    public function showAction($id)
    {
        $document = $this->findQuery('BaseUser', $id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find ' . $name . '.');
        }

        $dm = $this->getDocumentManager();

        $activeOrders = $dm->getRepository('Celsius3CoreBundle:Order')
                ->findForInstance($this->getInstance(), null, array(StateManager::STATE__CREATED, StateManager::STATE__SEARCHED, StateManager::STATE__REQUESTED, StateManager::STATE__APPROVAL_PENDING), $document)
                ->getQuery()
                ->execute();
        $readyOrders = $dm->getRepository('Celsius3CoreBundle:Order')
                ->findForInstance($this->getInstance(), null, StateManager::STATE__RECEIVED, $document)
                ->getQuery()
                ->execute();
        $historyOrders = $dm->getRepository('Celsius3CoreBundle:Order')
                ->findForInstance($this->getInstance(), null, array(StateManager::STATE__DELIVERED, StateManager::STATE__ANNULLED, StateManager::STATE__CANCELLED), $document)
                ->getQuery()
                ->execute();

        $messages = $this->get('fos_message.thread_manager')
                ->getParticipantSentThreadsQueryBuilder($document)
                ->getQuery()
                ->execute();

        return array(
            'element' => $document,
            'orders' => array(
                'active' => $activeOrders,
                'ready' => $readyOrders,
                'history' => $historyOrders,
            ),
            'messages' => $messages,
        );
    }

    /**
     * Displays a form to create a new BaseUser document.
     *
     * @Route("/new", name="admin_user_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('BaseUser', new BaseUser(), new BaseUserType($this->container, 'Celsius3\CoreBundle\Document\BaseUser', $this->getInstance()));
    }

    /**
     * Creates a new BaseUser document.
     *
     * @Route("/create", name="admin_user_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminBaseUser:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        $request = $this->getRequest();
        $document = new BaseUser();
        $form = $this->createForm(new BaseUserType($this->container, 'Celsius3\CoreBundle\Document\BaseUser', $this->getInstance()), $document);
        $form->bind($request);

        if ($form->isValid()) {
            $dm = $this->getDocumentManager();
            $dm->persist($document);
            $dm->flush();

            $this->get('celsius3_core.custom_field_helper')->processCustomFields($this->getInstance(), $form, $document);

            $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'The BaseUser was successfully created.');

            return $this->redirect($this->generateUrl('admin_user'));
        }

        $this->get('session')
                ->getFlashBag()
                ->add('error', 'There were errors creating the BaseUser.');

        return array(
            'document' => $document,
            'form' => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing BaseUser document.
     *
     * @Route("/{id}/edit", name="admin_user_edit", options={"expose"=true})
     * @Template()
     *
     * @param string $id
     *                   The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('BaseUser', $id, new BaseUserType($this->container, 'Celsius3\CoreBundle\Document\BaseUser', $this->getInstance(), true));
    }

    /**
     * Edits an existing BaseUser document.
     *
     * @Route("/{id}/update", name="admin_user_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminBaseUser:edit.html.twig")
     *
     * @param string $id
     *                   The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        $document = $this->findQuery('BaseUser', $id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find BaseUser.');
        }

        $editForm = $this->createForm(new BaseUserType($this->container, 'Celsius3\CoreBundle\Document\BaseUser', $this->getInstance(), true), $document);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $dm = $this->getDocumentManager();
            $dm->persist($document);
            $dm->flush();

            $this->get('celsius3_core.custom_field_helper')->processCustomFields($this->getInstance(), $editForm, $document);

            $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'The BaseUser was successfully edited.');

            return $this->redirect($this->generateUrl('admin_user_edit', array(
                                'id' => $id
            )));
        }

        $this->get('session')
                ->getFlashBag()
                ->add('error', 'There were errors editing the BaseUser.');

        return array(
            'document' => $document,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        );
    }

    /**
     * Displays a form to transform an existing BaseUser document.
     *
     * @Route("/{id}/transform", name="admin_user_transform")
     * @Template()
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function transformAction($id, Request $request)
    {
        if ($request->getMethod() === 'POST') {
            return $this->baseDoTransformAction($id, new UserTransformType($this->getInstance()), 'admin_user');
        }

        return $this->baseTransformAction($id, new UserTransformType($this->getInstance()));
    }

    /**
     * Enables a BaseUser document.
     *
     * @Route("/{id}/enable", name="admin_user_enable", options={"expose"=true})
     *
     * @param string $id
     *                   The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function enableAction($id)
    {
        return $this->baseEnableAction($id);
    }

    /**
     * Batch actions.
     *
     * @Route("/batch", name="admin_user_batch")
     *
     * @return array
     */
    public function batchAction()
    {
        return $this->baseBatch();
    }

    protected function batchEnable($element_ids)
    {
        return $this->baseBatchEnable($element_ids);
    }

    protected function batchUnion($element_ids)
    {
        return $this->render('Celsius3CoreBundle:AdminBaseUser:batchUnion.html.twig', $this->baseUnion('BaseUser', $element_ids));
    }

    /**
     * Unifies a group of Journal documents.
     *
     * @Route("/batch/doUnion", name="admin_user_doUnion")
     * @Method("post")
     *
     * @return array
     */
    public function doUnionAction()
    {
        $element_ids = $this->getRequest()->request->get('element');
        $main_id = $this->getRequest()->request->get('main');

        return $this->baseDoUnion('BaseUser', $element_ids, $main_id, 'admin_user', false);
    }
}