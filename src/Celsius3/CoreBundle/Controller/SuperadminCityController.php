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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Entity\City;
use Celsius3\CoreBundle\Form\Type\CityType;
use Celsius3\CoreBundle\Filter\Type\CityFilterType;

/**
 * Location controller.
 *
 * @Route("/superadmin/city")
 */
class SuperadminCityController extends BaseController
{

    /**
     * Lists all City entities.
     *
     * @Route("/", name="superadmin_city")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('City', $this->createForm(new CityFilterType()));
    }

    /**
     * Displays a form to create a new City entity.
     *
     * @Route("/new", name="superadmin_city_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('City', new City(), new CityType($this->getDirectory()));
    }

    /**
     * Creates a new City entity.
     *
     * @Route("/create", name="superadmin_city_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminCity:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('City', new City(), new CityType($this->getDirectory()), 'superadmin_city');
    }

    /**
     * Displays a form to edit an existing City entity.
     *
     * @Route("/{id}/edit", name="superadmin_city_edit")
     * @Template()
     *
     * @param string $id
     *                   The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('City', $id, new CityType($this->getDirectory()));
    }

    /**
     * Edits an existing City entity.
     *
     * @Route("/{id}/update", name="superadmin_city_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminCity:edit.html.twig")
     *
     * @param string $id
     *                   The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('City', $id, new CityType($this->getDirectory()), 'superadmin_city');
    }

    /**
     * Deletes a City entity.
     *
     * @Route("/{id}/delete", name="superadmin_city_delete")
     * @Method("post")
     *
     * @param string $id
     *                   The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function deleteAction($id)
    {
        return $this->baseDelete('City', $id, 'superadmin_city');
    }

    /**
     * Batch actions.
     *
     * @Route("/batch", name="superadmin_city_batch")
     *
     * @return array
     */
    public function batchAction()
    {
        return $this->baseBatch();
    }

    protected function batchUnion($element_ids)
    {
        return $this->render('Celsius3CoreBundle:SuperadminCity:batchUnion.html.twig', $this->baseUnion('City', $element_ids));
    }

    /**
     * Unifies a group of City entities.
     *
     * @Route("/doUnion", name="superadmin_city_doUnion")
     * @Method("post")
     *
     * @return array
     */
    public function doUnionAction()
    {
        $element_ids = $this->getRequest()->request->get('element');
        $main_id = $this->getRequest()->request->get('main');

        return $this->baseDoUnion('City', $element_ids, $main_id, 'superadmin_city');
    }
}