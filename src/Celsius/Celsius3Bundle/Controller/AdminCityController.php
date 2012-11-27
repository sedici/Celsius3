<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\City;
use Celsius\Celsius3Bundle\Form\Type\CityType;
use Celsius\Celsius3Bundle\Filter\Type\CityFilterType;

/**
 * Location controller.
 *
 * @Route("/admin/city")
 */
class AdminCityController extends BaseInstanceDependentController
{

    /**
     * Lists all City documents.
     *
     * @Route("/", name="admin_city")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('City', $this->createForm(new CityFilterType($this->getInstance())));
    }

    /**
     * Displays a form to create a new City document.
     *
     * @Route("/new", name="admin_city_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('City', new City(), new CityType($this->getInstance()));
    }

    /**
     * Creates a new City document.
     *
     * @Route("/create", name="admin_city_create")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:AdminCity:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('City', new City(), new CityType($this->getInstance()), 'admin_city');
    }

    /**
     * Displays a form to edit an existing City document.
     *
     * @Route("/{id}/edit", name="admin_city_edit")
     * @Template()
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('City', $id, new CityType($this->getInstance()));
    }

    /**
     * Edits an existing City document.
     *
     * @Route("/{id}/update", name="admin_city_update")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:AdminCity:edit.html.twig")
     * 
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('City', $id, new CityType($this->getInstance()), 'admin_city');
    }

    /**
     * Deletes a City document.
     *
     * @Route("/{id}/delete", name="admin_city_delete")
     * @Method("post")
     * 
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function deleteAction($id)
    {
        return $this->baseDelete('City', $id, 'admin_city');
    }

}
