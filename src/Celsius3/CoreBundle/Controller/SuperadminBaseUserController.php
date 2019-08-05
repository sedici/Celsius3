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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Form\Type\BaseUserType;
use Celsius3\CoreBundle\Form\Type\UserTransformType;
use Celsius3\CoreBundle\Form\Type\Filter\BaseUserFilterType;

/**
 * BaseUser controller.
 *
 * @Route("/superadmin/user")
 */
class SuperadminBaseUserController extends BaseUserController
{
    protected function getSortDefaults()
    {
        return array(
            'defaultSortFieldName' => 'e.surname',
            'defaultSortDirection' => 'asc',
        );
    }

    protected function listQuery($name)
    {
        return $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3CoreBundle:'.$name)
                        ->createQueryBuilder('e');
    }

    protected function findQuery($name, $id)
    {
        return $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3CoreBundle:'.$name)
                        ->find($id);
    }

    protected function getResultsPerPage()
    {
        return $this->container->getParameter('max_per_page');
    }

    protected function filter($name, $filter_form, $query)
    {
        return $this->get('celsius3_core.filter_manager')
                        ->filter($query, $filter_form, 'Celsius3\\CoreBundle\\Entity\\'.$name);
    }

    /**
     * Lists all BaseUser entities.
     *
     * @Route("/", name="superadmin_user")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('BaseUser', $this->createForm(BaseUserFilterType::class));
    }

    /**
     * Displays a form to create a new BaseUser entity.
     *
     * @Route("/new", name="superadmin_user_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('BaseUser', new BaseUser(), BaseUserType::class, array(
            'instance' => $this->getDirectory(),
        ));
    }

    /**
     * Creates a new BaseUser entity.
     *
     * @Route("/create", name="superadmin_user_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminBaseUser:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('BaseUser', new BaseUser(), BaseUserType::class, array(
            'instance' => $this->getDirectory(),
        ), 'superadmin_user');
    }

    /**
     * Displays a form to edit an existing BaseUser entity.
     *
     * @Route("/{id}/edit", name="superadmin_user_edit")
     * @Template()
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('BaseUser', $id, BaseUserType::class, array(
            'instance' => $this->getDirectory(),
            'editing' => true,
        ));
    }

    /**
     * Edits an existing BaseUser entity.
     *
     * @Route("/{id}/update", name="superadmin_user_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminBaseUser:edit.html.twig")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('BaseUser', $id, BaseUserType::class, array(
            'instance' => $this->getDirectory(),
            'editing' => true,
        ), 'superadmin_user');
    }

    /**
     * Displays a form to transform an existing BaseUser entity.
     *
     * @Route("/{id}/transform", name="superadmin_user_transform")
     * @Template()
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function transformAction($id, Request $request)
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

        return $this->render('Celsius3CoreBundle:SuperadminBaseUser:transform.html.twig', $response);
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
