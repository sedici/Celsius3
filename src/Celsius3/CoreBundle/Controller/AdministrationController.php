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

/**
 * Administration controller
 *
 * @Route("/admin")
 */
class AdministrationController extends BaseInstanceDependentController
{

    /**
     * @Route("/", name="administration")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/search", name="administration_search")
     * @Template()
     *
     * @return array
     */
    public function searchAction()
    {
        $keyword = $this->getRequest()->query->get('keyword');

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($this->get('celsius3_core.search_manager')->search('Order', $keyword, $this->getInstance()), $this->get('request')->query->get('page', 1)/* page number */, $this->container->getParameter('max_per_page')/* limit per page */);

        return array(
            'keyword' => $keyword,
            'pagination' => $pagination,
        );
    }

    /**
     * @Route("/ajax", name="admin_ajax")
     */
    public function ajaxAction()
    {
        return $this->ajax($this->getInstance());
    }

    /**
     * @Route("/{id}/change", name="administration_change_context")
     */
    public function changeContextAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $instance = $em->getRepository('Celsius3CoreBundle:Instance')->find($id);

        $user = $this->getUser();
        if (!$user->getAdministeredInstances()->contains($user->getInstance())) {
            $user->getAdministeredInstances()->add($user->getInstance());
        }

        if (!$instance || !$user->getAdministeredInstances()->contains($instance)) {
            return $this->createNotFoundException('Instance not found');
        }

        $this->get('session')->set('instance_id', $instance->getId());
        $this->get('session')->set('instance_url', $instance->getUrl());

        return $this->redirect($this->generateUrl('administration'));
    }
}