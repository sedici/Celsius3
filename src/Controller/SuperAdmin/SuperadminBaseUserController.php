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

namespace Celsius3\Controller\SuperAdmin;

use Celsius3\Controller\BaseUserController;
use Celsius3\Entity\BaseUser;
use Celsius3\Form\Type\Filter\BaseUserFilterType;
use Celsius3\Form\Type\UserTransformType;
use Celsius3\Form\Type\BaseUserType;
use Celsius3\Helper\ConfigurationHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * BaseUser controller.
 *
 * @Route("/superadmin/user")
 */
class SuperadminBaseUserController extends BaseUserController
{

    protected function listQuery($name)
    {
        return $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3:'.$name)
                        ->createQueryBuilder('e');
    }

    protected function findQuery($name, $id)
    {
        return $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3:'.$name)
                        ->find($id);
    }

    protected function getResultsPerPage()
    {
        return $this->container->getParameter('max_per_page');
    }

    protected function filter($name, $filter_form, $query)
    {
        return $this->get('celsius3_core.filter_manager')
                        ->filter($query, $filter_form, 'Celsius3\\Entity\\'.$name);
    }

    /**
     * Lists all BaseUser entities.
     *
     * @Route("/", name="superadmin_user")
     */
    public function index(): Response
    {
        return $this->render(
            'Superadmin/BaseUser/index.html.twig',
            $this->baseIndex('BaseUser', $this->createForm(BaseUserFilterType::class))
        );
    }

    /**
     * Displays a form to create a new BaseUser entity.
     *
     * @Route("/new", name="superadmin_user_new")
     */
    public function new(): Response
    {
        return $this->render(
            'Superadmin/BaseUser/new.html.twig',
            $this->baseNew('BaseUser', new BaseUser(), BaseUserType::class, [
                'instance' => $this->getDirectory(),
            ])
        );
    }

    /**
     * Creates a new BaseUser entity.
     *
     * @Route("/create", name="superadmin_user_create", methods={"POST"})
     */
    public function create(): Response
    {
        return $this->render(
            'Superadmin/BaseUser/new.html.twig',
            $this->baseCreate('BaseUser', new BaseUser(), BaseUserType::class, [
                'instance' => $this->getDirectory(),
            ], 'superadmin_user')
        );
    }

    /**
     * Displays a form to edit an existing BaseUser entity.
     *
     * @Route("/{id}/edit", name="superadmin_user_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id): Response
    {
        return $this->render(
            'Superadmin/BaseUser/edit.html.twig',
            $this->baseEdit('BaseUser', $id, BaseUserType::class, [
                'instance' => $this->getDirectory(),
                'editing' => true,
            ])
        );
    }

    /**
     * Edits an existing BaseUser entity.
     *
     * @Route("/{id}/update", name="superadmin_user_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update($id): Response
    {
        return $this->render(
            'Superadmin/BaseUser/edit.html.twig',
            $this->baseUpdate(
                'BaseUser',
                $id,
                BaseUserType::class,
                [
                    'instance' => $this->getDirectory(),
                    'editing' => true,
                ],
                'superadmin_user'
            )
        );
    }

    /**
     * Displays a form to transform an existing BaseUser entity.
     *
     * @Route("/{id}/transform", name="superadmin_user_transform")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function transform($id, Request $request)
    {
        $entity = $this->findQuery('BaseUser', $id);

        if ($request->getMethod() === 'POST') {
            return $this->baseDoTransformAction($id, UserTransformType::class, array(
                'user' => $entity,
                'user_actual'=>$this->getUser()
            ), 'superadmin_user');
        }

        $response = $this->baseTransformAction($id, UserTransformType::class, array(
            'user' => $entity,
            'user_actual'=>$this->getUser()
        ));

        if($response instanceof RedirectResponse){
            return $response;
        }

        return $this->render('Celsius3:SuperadminBaseUser:transform.html.twig', $response);
    }

    /**
     * Enables a BaseUser entity.
     *
     * @Route("/{id}/enable", name="superadmin_user_enable")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function enableAction($id)
    {
        return $this->baseEnableAction($id);
    }

    protected function getUserListRoute()
    {
        return 'superadmin_user';
    }
}
