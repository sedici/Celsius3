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

use Celsius3\Entity\BaseUser;
use Celsius3\Entity\City;
use Celsius3\Entity\Country;
use Celsius3\Entity\Instance;
use FOS\RestBundle\Context\Context;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Celsius3\Entity\Institution;
use Celsius3\Exception\Exception;

/**
 * User controller.
 *
 * @Route("/admin/rest/institution")
 */
class AdminInstitutionRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     *
     * @Get("/intercambio/rest/institution", name="admin_rest_institution_intercambio", options={"expose"=true})
     */
    public function getInteractionInstitutions(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $city_id = null;
        $filter = null;
        if ($request->query->has('filter') && $request->query->get('filter') !== '') {
            $filter = $request->query->get('filter');
        }
        $country_id = $request->query->get('country_id');
        $hive = $this->getInstance()->getHive();

        $institutions = $em->getRepository(Institution::class)
            ->findForInstanceAndGlobal($this->getInstance(), $this->getDirectory(), true, $hive, $country_id, $city_id, $filter)
            ->getQuery()->getResult();
        $view = $this->view(array_values($institutions), 200)->setFormat('json');

        $context = new Context();
        $context->addGroup('administration_order_show');
        $view->setContext($context);

        return $this->handleView($view);
    }




    /**
     * GET Route annotation.
     *
     * @Get("/parent/{parent_id}", name="admin_rest_institution_parent_get", options={"expose"=true})
     */
    public function getInstitutionByParent($parent_id)
    {
        $em = $this->getDoctrine()->getManager();

        $institutions = $em->getRepository(Institution::class)
                ->findBy(array('parent' => $parent_id));

        $view = $this->view(array_values($institutions), 200)->setFormat('json');

        $context = new Context();
        $context->addGroup('administration_order_show');
        $view->setContext($context);

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     *
     * @Get("/{id}/get", name="admin_rest_institution_get", options={"expose"=true})
     */
    public function getInstitution($id)
    {
        $em = $this->getDoctrine()->getManager();

        $institution = $em->getRepository(Institution::class)->find($id);

        if (!$institution) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.institution');
        }

        $view = $this->view($institution, 200)->setFormat('json');

        $context = new Context();
        $context->addGroup('institution_show');
        $view->setContext($context);

        return $this->handleView($view);
    }

    /**
     * @Get("/{id}/users", name="admin_rest_institution_users_get", options={"expose"=true})
     */
    public function getInstitutionUsers($id)
    {
        $users = $this->getDoctrine()->getRepository(BaseUser::class)->getInstitutionUsers($id);

        $view = $this->view(array_values($users), 200)->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     *
     * @Get("/{country_id}/{city_id}", defaults={"city_id" = null}, name="admin_rest_institution", options={"expose"=true})
     */
    public function getInstitutions($country_id, $city_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $filter = null;
        if ($request->query->has('filter') && $request->query->get('filter') !== '') {
            $filter = $request->query->get('filter');
        }

        $hive = $this->getInstance()->getHive();

        $institutions = $em->getRepository(Institution::class)
                        ->findForInstanceAndGlobal($this->getInstance(), $this->getDirectory(), true, $hive, $country_id, $city_id, $filter)
                        ->getQuery()->getResult();

        $view = $this->view(array_values($institutions), 200)->setFormat('json');

        $context = new Context();
        $context->addGroup('administration_order_show');
        $view->setContext($context);

        return $this->handleView($view);
    }



    /**
     * @Post("/create", name="admin_rest_institution_create", options={"expose"=true})
     */
    public function createInstitution(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $institution = new Institution();
        $institution->setName($request->request->get('name'));
        $institution->setAbbreviation($request->request->get('abbreviation'));
        $institution->setWebsite($request->request->get('website'));
        $institution->setAddress($request->request->get('address'));

        $institution->setCountry($em->getRepository(Country::class)->find($request->request->get('country')));
        if (!empty($request->request->get('city'))) {
            $institution->setCity($em->getRepository(City::class)->find($request->request->get('city')));
        }
        if (!empty($request->request->get('institution'))) {
            $institution->setParent($em->getRepository(Institution::class)->find($request->request->get('institution')));
        }
        $institution->setInstance($em->getRepository(Instance::class)->find($request->request->get('instance')));

        $validator = $this->get('validator');
        $errors = $validator->validate($institution);

        if (count($errors) > 0) {
            $view = $this->view(array('hasErrors' => true, 'errors' => $errors), 200)->setFormat('json');

            return $this->handleView($view);
        }

        $em->persist($institution);
        $em->flush($institution);

        $view = $this->view(array('hasErrors' => false, 'institution' => $institution), 200)->setFormat('json');

        return $this->handleView($view);
    }
}
