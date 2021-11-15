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

use Celsius3\Entity\City;
use Celsius3\Entity\Country;
use Celsius3\Entity\Instance;
use Celsius3\Entity\Institution;
use Celsius3\Entity\News;
use Celsius3\Helper\InstanceHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Celsius3\Exception\Exception;
use Knp\Component\Pager\Paginator;

/**
 * Public controller.
 *
 * @Route("/public")
 */
class PublicController extends AbstractController // BaseInstanceDependentController
{
    private $entityManager;
    private $instanceHelper;
    private $paginator;
    private $maxPerPage;

    public function __construct(
        EntityManagerInterface $entityManager,
        InstanceHelper $instanceHelper,
        Paginator $paginator,
        string $maxPerPage
    )
    {
        $this->entityManager = $entityManager;
        $this->instanceHelper = $instanceHelper;
        $this->paginator = $paginator;
        $this->maxPerPage = $maxPerPage;
    }

    protected function getInstance(): ?Instance
    {
        return $this->instanceHelper->getSessionOrUrlInstance();
    }

    /**
     * @Route("/", name="public_index")
     */
    public function index()
    {
        return $this->render('Public/index.html.twig', [
            'instance' => $this->getInstance(),
            'lastNews' => ($this->getInstance()) ? $this->entityManager
                    ->getRepository(News::class)
                    ->findLastNews($this->getInstance()) : [],
        ]);
    }

    /**
     * @Route("/information", name="public_information")
     */
    public function information()
    {
        $instance = $this->getInstance();
        if ($instance && !boolval($instance->get('home_information_visible')->getValue())) {
            return $this->redirectToRoute('public_index');
        }

        return $this->render('Public/information.html.twig', [
            'instance' => $this->getInstance(),
        ]);
    }

    /**
     * @Route("/news", name="public_news")
     */
    public function news(Request $request)
    {
        $instance = $this->getInstance();
        if ($instance && !boolval($instance->get('home_news_visible')->getValue())) {
            return $this->redirectToRoute('public_index');
        }

        $news = $this->entityManager
                ->getRepository(News::class)
                ->findByInstanceQB($this->getInstance());

        $paginator = $this->paginator;
        $pagination = $paginator->paginate($news, $request->query->get('page', 1), $this->maxPerPage);

        return $this->render('Public/news.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/statistics", name="public_statistics", options={"expose"=true})
     */
    public function statistics()
    {
        $instance = $this->getInstance();
        if ($instance && !boolval($instance->get('home_statistics_visible')->getValue())) {
            return $this->redirectToRoute('public_index');
        }

        return $this->render('Public/statistics.html.twig', []);
    }

    /**
     * @Route("/countries", name="public_countries", options={"expose"=true})
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function countries(): Response
    {
        $countries = $this->getDoctrine()->getManager()
            ->getRepository(Country::class)
            ->getAllOrderedByNameQB()
            ->getQuery()->execute();

        $response = [];
        foreach ($countries as $country) {
            $response[] = ['value' => $country->getId(), 'name' => ucfirst(strtolower($country->getName()))];
        }

        return new Response(json_encode($response));
    }

    /**
     * @Route("/cities", name="public_cities", options={"expose"=true})
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function cities(Request $request): Response
    {
        if (!$request->query->has('country_id')) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.country');
        }

        $cities = $this->getDoctrine()->getManager()
            ->getRepository(City::class)
            ->findForCountry($request->query->get('country_id'));

        $response = [];

        foreach ($cities as $city) {
            $response[] = ['value' => $city->getId(), 'name' => $city->getName()];
        }

        return new Response(json_encode($response));
    }

    /**
     * @Route("/institutions", name="public_institutions", options={"expose"=true})
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function institutions(Request $request)
    {
        if (!$request->query->has('country_id')) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.country');
        }

        $institutions = $this->getDoctrine()->getManager()
            ->getRepository(Institution::class)
            ->findByCountry($request->query->get('country_id'), $this->getInstance(), $this->getDirectory());

        $response = [];
        foreach ($institutions as $institution) {
            $response[] = [
                'value' => $institution->getId(),
                'name' => $institution->getName(),
            ];
        }

        return new Response(json_encode($response));
    }

    /**
     * @Route("/institutionsFull", name="public_institutions_full", options={"expose"=true})
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function institutionsFull(Request $request): Response
    {
        if (!$request->query->has('country_id') && !$request->query->has('city_id') && !$request->query->has('institution_id')) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $institutions = $em->getRepository(Institution::class)
                ->findForCountryOrCity($request->query->get('country_id'), $request->query->get('city_id'), $this->getDirectory(), $this->getInstance());

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

                $instAbbr = ' | '.(($institution['abbreviation']) ?: $institution['name']);

                $response[] = [
                    'value' => $institution['id'],
                    'hasChildren' => count($children) > 0,
                    'name' => $institution['name'].(($institution['abbreviation']) ? ' ('.$institution['abbreviation'].')' : ''),
                    'level' => $level,
                    'children' => $this->getChildrenInstitution($institutions, $children, $level + 1, $instAbbr),
                ];
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
     */
    public function help()
    {
        $instance = $this->getInstance();
        if ($instance && !boolval($instance->get('home_help_visible')->getValue())) {
            return $this->redirectToRoute('public_index');
        }

        return $this->render('Public/help.html.twig', ['staff' => $this->getInstance()->get('instance_staff')->getValue()]);
    }
}
