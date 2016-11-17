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

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Celsius3\CoreBundle\Exception\Exception;

/**
 * Public controller.
 *
 * @Route("/public")
 */
class PublicController extends BaseInstanceDependentController
{
    protected function getInstance()
    {
        return $this->get('celsius3_core.instance_helper')->getSessionOrUrlInstance();
    }

    /**
     * @Route("/", name="public_index")
     * @Template()
     */
    public function indexAction()
    {
        return array(
            'instance' => $this->getInstance(),
            'lastNews' => $this->getDoctrine()->getManager()
                    ->getRepository('Celsius3CoreBundle:News')
                    ->findLastNews($this->getInstance()),
            'show_news' => $this->getInstance()->get('show_news')->getValue(),
        );
    }

    /**
     * @Route("/information", name="public_information")
     * @Template()
     */
    public function informationAction()
    {
        return array(
            'instance' => $this->getInstance(),
        );
    }

    /**
     * @Route("/news", name="public_news")
     * @Template()
     */
    public function newsAction(Request $request)
    {
        $news = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:News')
                ->findByInstanceQB($this->getInstance());

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($news, $request->query->get('page', 1), $this->container->getParameter('max_per_page'));

        return array(
            'pagination' => $pagination,
        );
    }

    /**
     * @Route("/statistics", name="public_statistics", options={"expose"=true})
     * @Template()
     */
    public function statisticsAction()
    {
        return array();
    }

    /**
     * @Route("/countries", name="public_countries", options={"expose"=true})
     * @Template()
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function countriesAction()
    {
        $countries = $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3CoreBundle:Country')->findAll();

        $response = array();
        foreach ($countries as $country) {
            $response[] = array('value' => $country->getId(), 'name' => $country->getName());
        }

        return new Response(json_encode($response));
    }

    /**
     * @Route("/cities", name="public_cities", options={"expose"=true})
     * @Template()
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function citiesAction(Request $request)
    {
        if (!$request->query->has('country_id')) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.country');
        }

        $cities = $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3CoreBundle:City')
                        ->findForCountry($request->query->get('country_id'));

        $response = array();

        foreach ($cities as $city) {
            $response[] = array('value' => $city->getId(), 'name' => $city->getName());
        }

        return new Response(json_encode($response));
    }

    /**
     * @Route("/institutions", name="public_institutions", options={"expose"=true})
     * @Template()
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function institutionsAction(Request $request)
    {
        if (!$request->query->has('country_id')) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.country');
        }

        $institutions = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:Institution')
                ->findByCountry($request->query->get('country_id'));

        $response = array();
        foreach ($institutions as $institution) {
            $response[] = array(
                'value' => $institution->getId(),
                'name' => $institution->getName(),
            );
        }

        return new Response(json_encode($response));
    }

    /**
     * @Route("/institutionsFull", name="public_institutions_full", options={"expose"=true})
     * @Template()
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function institutionsFullAction(Request $request)
    {
        if (!$request->query->has('country_id') && !$request->query->has('city_id') && !$request->query->has('institution_id')) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $institutions = $em->getRepository('Celsius3CoreBundle:Institution')
                ->findForCountryOrCity($request->query->get('country_id'), $request->query->get('city_id'));

        $actual = array_filter($institutions, function ($i) {
            return is_null($i['parent_id']);
        });
        $institutions = array_diff_key($institutions, $actual);
        $response = array();
        foreach ($actual as $institution) {
            $level = 0;
            if (($request->query->get('filter') === 'liblink' && $institution['hive_id'] === $this->getInstance()->getHive()->getId()) ||
                    ($request->query->get('filter') === 'celsius3' && $institution['celsiusInstance']) ||
                    ($request->query->get('filter') === '')) {
                $children = array_filter($institutions, function ($i) use ($institution) {
                    return $i['parent_id'] === $institution['id'];
                });

                $instAbbr = ' | '.(($institution['abbreviation']) ? $institution['abbreviation'] : $institution['name']);

                $response[] = array(
                    'value' => $institution['id'],
                    'hasChildren' => count($children) > 0,
                    'name' => $institution['name'].(($institution['abbreviation']) ? ' ('.$institution['abbreviation'].')' : ''),
                    'level' => $level,
                    'children' => $this->getChildrenInstitution($institutions, $children, $level + 1, $instAbbr),
                );
            }
        }

        return new Response(json_encode($response));
    }

    protected function getChildrenInstitution(array &$all, array $institutions, $level, $parent)
    {
        $response = array();
        $all = array_diff_key($all, $institutions);
        if (count($institutions) > 0) {
            foreach ($institutions as $institution) {
                $children = array_filter($all, function ($i) use ($institution) {
                    return $i['parent_id'] === $institution['id'];
                });

                $response[] = array(
                    'value' => $institution['id'],
                    'hasChildren' => count($children) > 0,
                    'name' => $institution['name'].(($institution['abbreviation']) ? ' ('.$institution['abbreviation'].')' : '').$parent,
                    'level' => $level,
                    'children' => $this->getChildrenInstitution($all, $children, $level + 1, $parent),
                );
            }
        }

        return $response;
    }

    /**
     * @Route("/help", name="public_help")
     * @Template()
     */
    public function helpAction()
    {
        return array('staff' => $this->getInstance()->get('instance_staff')->getValue());
    }
}
