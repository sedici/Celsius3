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

use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Manager\InstanceManager;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\Entity\Country;
use Celsius3\Form\Type\CountryType;
use Celsius3\Form\Type\Filter\CountryFilterType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormInterface;

/**
 * Order controller.
 *
 * @Route("/superadmin/country")
 */
class SuperadminCountryController extends BaseController
{

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var InstanceManager
     */
    private $instanceManager;
    public function __construct(
        PaginatorInterface $paginator,
        ConfigurationHelper $configurationHelper,
        InstanceManager $instanceManager
    ) {
        $this->paginator = $paginator;
        $this->instanceManager=$instanceManager;
        $this->setConfigurationHelper($configurationHelper);

    }
    protected function getSortDefaults()
    {
        return array(
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        );
    }

    protected function listQuery($name)
    {
        $valor=$name;
//        $class = new \ReflectionClass($valor);
        return $this->getDoctrine()->getManager()
            ->getRepository(Country::class)
            ->createQueryBuilder('e');
    }


    protected function baseIndex($name, FormInterface $filter_form = null,$paginator)
    {

        $query = $this->listQuery($name);
        $request = $this->get('request_stack')->getCurrentRequest();
        if (!is_null($filter_form)) {
            $filter_form = $filter_form->handleRequest($request);
            //  $query = $this->filter($name, $filter_form, $query);
        }
        //    $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate($query, $request->query->get('page', 1)/* page number */, $this->getResultsPerPage()/* limit per page */, $this->getSortDefaults());

        return array(
            'pagination' => $pagination,
            'filter_form' => (!is_null($filter_form)) ? $filter_form->createView() : $filter_form,
        );
    }


    /**
     * Lists all Country entities.
     *
     * @Route("/", name="superadmin_country")
     */
    public function index()
    {
        return $this->render(
            'Superadmin/Country/index.html.twig',
            $this->baseIndex('Country', $this->createForm(CountryFilterType::class),$this->paginator)
        );
    }
    protected function getDirectory()
    {
        return $this->instanceManager->getDirectory();
    }
    /**
     * Displays a form to create a new Country entity.
     *
     * @Route("/new", name="superadmin_country_new")
     */
    public function new(): Response
    {
        return $this->render(
            'Admin/Institution/new.html.twig',
            $this->baseNew('Country', new Country(), CountryType::class, [
                'instance' => $this->getDirectory(),
            ])
        );
    }

    /**
     * Creates a new Country entity.
     *
     * @Route("/create", name="superadmin_country_create", methods={"POST"})
     */
    public function create()
    {
        return $this->render('Superadmin/Country/new.html.twig', $this->baseCreate('Country', new Country(), CountryType::class, array(
            'instance' => $this->getDirectory(),
        ), 'superadmin_country'));
    }

    /**
     * Displays a form to edit an existing Country entity.
     *
     * @Route("/{id}/edit", name="superadmin_country_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id): Response
    {
        return $this->render(
            'Admin/Institution/edit.html.twig',
            $this->baseEdit('Country', $id, CountryType::class, [
                'instance' => $this->getDirectory(),
            ])
        );
    }

    /**
     * Edits an existing Country entity.
     *
     * @Route("/{id}/update", name="superadmin_country_update", methods={"POST"})
     *
     * @param string $id
     *                   The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update($id)
    {
        return $this->render('Superadmin/Country/edit.html.twig', $this->baseUpdate('Country', $id, CountryType::class, array(
            'instance' => $this->getDirectory(),
        ), 'superadmin_country'));
    }

    /**
     * Batch actions.
     *
     * @Route("/batch", name="superadmin_country_batch")
     *
     * @return array
     */
    public function batch()
    {
        return $this->baseBatch();
    }

    protected function batchUnion($element_ids)
    {
        return $this->render('Superadmin/Country/batchUnion.html.twig', $this->baseUnion('Country', $element_ids));
    }

    /**
     * Unifies a group of Country entities.
     *
     * @Route("/doUnion", name="superadmin_country_doUnion", methods={"POST"})
     */
    public function doUnion()
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $element_ids = $request->request->get('element');
        $main_id = $request->request->get('main');

        return $this->baseDoUnion(Country::class, $element_ids, $main_id, 'superadmin_country');
    }
}
