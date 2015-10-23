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

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use JMS\Serializer\SerializationContext;

/**
 * User controller.
 *
 * @Route("/admin/rest/institution")
 */
class AdminInstitutionRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("/parent/{parent_id}", name="admin_rest_institution_parent_get", options={"expose"=true})
     */
    public function getInstitutionByParentAction($parent_id)
    {
        $context = SerializationContext::create()->setGroups(array('administration_order_show'));

        $em = $this->getDoctrine()->getManager();

        $institutions = $em->getRepository('Celsius3CoreBundle:Institution')
                ->findBy(array('parent' => $parent_id,));

        $view = $this->view(array_values($institutions), 200)->setFormat('json');
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{country_id}/{city_id}", defaults={"city_id" = null}, name="admin_rest_institution", options={"expose"=true})
     */
    public function getInstitutionsAction($country_id, $city_id, Request $request)
    {
        $context = SerializationContext::create()->setGroups(array('administration_order_show'));

        $em = $this->getDoctrine()->getManager();

        $filter = null;
        if ($request->query->has('filter') && $request->query->get('filter') !== '') {
            $filter = $request->query->get('filter');
        }

        $hive = $this->getInstance()->getHive();

        $institutions = $em->getRepository('Celsius3CoreBundle:Institution')
                ->findForInstanceAndGlobal($this->getInstance(), $this->getDirectory(), $hive, $country_id, $city_id, $filter);

        $view = $this->view(array_values($institutions), 200)->setFormat('json');
        $view->setSerializationContext($context);

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/{id}", name="admin_rest_institution_get", options={"expose"=true})
     */
    public function getInstitutionAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $institution = $em->getRepository('Celsius3CoreBundle:Institution')->find($id);

        if (!$institution) {
            return $this->createNotFoundException('Institution not found.');
        }

        $view = $this->view($institution, 200)->setFormat('json');

        return $this->handleView($view);
    }
}
