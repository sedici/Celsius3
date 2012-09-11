<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\City;
use Celsius\Celsius3Bundle\Form\Type\CityType;

/**
 * Location controller.
 *
 * @Route("/superadmin/city")
 */
class SuperadminCityController extends BaseController
{

    /**
     * Lists all City documents.
     *
     * @Route("/", name="superadmin_city")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('City');
    }

    /**
     * Displays a form to create a new City document.
     *
     * @Route("/new", name="superadmin_city_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('City', new City(), new CityType());
    }

    /**
     * Creates a new City document.
     *
     * @Route("/create", name="superadmin_city_create")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:SuperadminCity:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('City', new City(), new CityType(), 'superadmin_city');
    }

    /**
     * Displays a form to edit an existing City document.
     *
     * @Route("/{id}/edit", name="superadmin_city_edit")
     * @Template()
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('City', $id, new CityType());
    }

    /**
     * Edits an existing City document.
     *
     * @Route("/{id}/update", name="superadmin_city_update")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:SuperadminCity:edit.html.twig")
     * 
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('City', $id, new CityType(), 'superadmin_city');
    }

    /**
     * Deletes a City document.
     *
     * @Route("/{id}/delete", name="superadmin_city_delete")
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
        return $this->baseDelete('City', $id, 'superadmin_city');
    }

}
