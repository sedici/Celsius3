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

namespace Celsius3\CoreBundle\Controller;

use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Exception\Exception;
use Celsius3\CoreBundle\Form\Type\BaseUserType;
use Celsius3\CoreBundle\Form\Type\Filter\BaseUserFilterType;
use Celsius3\CoreBundle\Form\Type\UserTransformType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * BaseUser controller.
 *
 * @Route("/admin/user")
 */
class AdminBaseUserController extends BaseUserController
{
    /**
     * Lists all BaseUser entities.
     *
     * @Route("/", name="admin_user")
     */
    public function indexAction()
    {
        $parameters = $this->baseIndex('BaseUser', $this->createForm(BaseUserFilterType::class, null, array(
                            'instance' => $this->getInstance(),
        )));

        return $this->render('Celsius3CoreBundle:AdminBaseUser:index.html.twig', $parameters);
    }

    /**
     * Shows the data of a user.
     *
     * @Route("/{id}/show", name="admin_user_show", options={"expose"=true})
     */
    public function showAction($id)
    {
        $entity = $this->findQuery('BaseUser', $id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.user');
        }

        $messages = $this->get('fos_message.thread_manager')
                        ->getParticipantSentThreadsQueryBuilder($entity)
                        ->getQuery()->getResult();

        $parameters = array(
            'element' => $entity,
            'messages' => $messages,
            'resultsPerPage' => $this->getResultsPerPage(),
        );

        return $this->render('Celsius3CoreBundle:AdminBaseUser:show.html.twig', $parameters);
    }

    /**
     * Displays a form to create a new BaseUser entity.
     *
     * @Route("/new", name="admin_user_new")
     */
    public function newAction()
    {
        $parameters = $this->baseNew('BaseUser', new BaseUser(), BaseUserType::class, array('validation_groups' => 'Registration'));

        return $this->render('Celsius3CoreBundle:AdminBaseUser:new.html.twig', $parameters);
    }

    /**
     * Creates a new BaseUser entity.
     *
     * @Route("/create", name="admin_user_create")
     * @Method("post")
     */
    public function createAction(Request $request)
    {
        return $this->baseCreateAction($request, 'Celsius3CoreBundle:AdminBaseUser:new.html.twig', array('validation_groups' => ['Registration', 'Default']));
    }

    /**
     * Displays a form to edit an existing BaseUser entity.
     *
     * @Route("/{id}/edit", name="admin_user_edit", options={"expose"=true})
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function editAction($id)
    {
        $parameters = $this->baseEdit('BaseUser', $id, BaseUserType::class, array('editing' => true));

        return $this->render('Celsius3CoreBundle:AdminBaseUser:edit.html.twig', $parameters);
    }

    /**
     * Edits an existing BaseUser entity.
     *
     * @Route("/{id}/update", name="admin_user_update")
     * @Method("post")
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function updateAction($id, Request $request)
    {
        $entity = $this->findQuery('BaseUser', $id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.user');
        }

        $editForm = $this->createForm(BaseUserType::class, $entity, array(
            'editing' => true,
            'validation_groups' => [ 'Profile', 'Default' ]
        ));

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('celsius3_core.custom_field_helper')->processCustomFields($this->getInstance(), $editForm, $entity);

            $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'The BaseUser was successfully edited.');

            return $this->redirect($this->generateUrl('admin_user_edit', array('id' => $id)));
        }

        $this->get('session')
                ->getFlashBag()
                ->add('error', 'There were errors editing the BaseUser.');

        $parameters = array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        );

        return $this->render('Celsius3CoreBundle:AdminBaseUser:edit.html.twig', $parameters);
    }

    /**
     * Displays a form to transform an existing BaseUser entity.
     *
     * @Route("/{id}/transform", name="admin_user_transform")
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function transformAction($id, Request $request)
    {
        $entity = $this->findQuery('BaseUser', $id);

        if ($request->getMethod() === 'POST') {
            return $this->baseDoTransformAction($id, UserTransformType::class, array(
                        'instance' => $this->getInstance(),
                        'user' => $entity,
                         'user_actual'=>$this->getUser()
                            ), 'admin_user');
        }

        $response = $this->baseTransformAction($id, UserTransformType::class, array(
                    'instance' => $this->getInstance(),
                    'user' => $entity,
                    'user_actual'=>$this->getUser()
        ));

        if($response instanceof RedirectResponse){
            return $response;
        }

        return $this->render('Celsius3CoreBundle:AdminBaseUser:transform.html.twig', $response);
    }

    /**
     * Enables a BaseUser entity.
     *
     * @Route("/{id}/enable", name="admin_user_enable", options={"expose"=true})
     *
     * @param string $id
     *                   The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
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
     * Unifies a group of Journal entities.
     *
     * @Route("/batch/doUnion", name="admin_user_doUnion")
     * @Method("post")
     *
     * @return array
     */
    public function doUnionAction(Request $request)
    {
        $element_ids = $request->request->get('element');
        $main_id = $request->request->get('main');

        return $this->baseDoUnion('BaseUser', $element_ids, $main_id, 'admin_user', false);
    }

    protected function getUserListRoute()
    {
        return 'admin_user';
    }

    /**
     * Shows the data of a user.
     *
     * @Route("/switch-user", name="switch_user")
     * @Template()
     *
     * @return array
     */
    public function switchUserAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $userManager = $this->get('fos_user.user_manager');
            $user = $userManager->findUserByUsername($_switch_user);
            $firewallName = 'secured_area';
            $token = new UsernamePasswordToken($user, $user->getPassword(), $firewallName, $user->getRoles());
            $this->container->get('security.token_storage')->setToken($token);
        }

        return $this->redirectToRoute('user_index', array());
    }
}
