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
use Celsius3\CoreBundle\Entity\Country;
use Celsius3\CoreBundle\Form\Type\CountryType;
use Celsius3\CoreBundle\Form\Type\Filter\CountryFilterType;

/**
 * Order controller.
 *
 * @Route("/admin/country")
 */
class AdminCountryController extends BaseInstanceDependentController
{
    protected function listQuery($name)
    {
        return $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3CoreBundle:'.$name)
                        ->findForInstanceAndGlobal($this->getInstance(), $this->getDirectory());
    }

    protected function getSortDefaults()
    {
        return array(
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        );
    }

    /**
     * Lists all Country entities.
     *
     * @Route("/", name="admin_country")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('Country', $this->createForm(CountryFilterType::class, null, array(
            'instance' => $this->getInstance(),
        )));
    }

    /**
     * Displays a form to create a new Country entity.
     *
     * @Route("/new", name="admin_country_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('Country', new Country(), CountryType::class, array(
            'instance' => $this->getInstance(),
        ));
    }

    /**
     * Creates a new Country entity.
     *
     * @Route("/create", name="admin_country_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminCountry:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Country', new Country(), CountryType::class, array(
            'instance' => $this->getInstance(),
        ), 'admin_country');
    }

    /**
     * Displays a form to edit an existing Country entity.
     *
     * @Route("/{id}/edit", name="admin_country_edit")
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
        return $this->baseEdit('Country', $id, CountryType::class, array(
            'instance' => $this->getInstance(),
        ));
    }

    /**
     * Edits an existing Country entity.
     *
     * @Route("/{id}/update", name="admin_country_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminCountry:edit.html.twig")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('Country', $id, CountryType::class, array(
            'instance' => $this->getInstance(),
        ), 'admin_country');
    }
}
