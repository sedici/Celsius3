<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\Country;
use Celsius\Celsius3Bundle\Form\Type\CountryType;
use Celsius\Celsius3Bundle\Filter\Type\CountryFilterType;

/**
 * Order controller.
 *
 * @Route("/admin/country")
 */
class AdminCountryController extends BaseInstanceDependentController
{

    /**
     * Lists all Country documents.
     *
     * @Route("/", name="admin_country")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('Country', $this->createForm(new CountryFilterType($this->getInstance())));
    }

    /**
     * Displays a form to create a new Country document.
     *
     * @Route("/new", name="admin_country_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('Country', new Country(), new CountryType($this->getInstance()));
    }

    /**
     * Creates a new Country document.
     *
     * @Route("/create", name="admin_country_create")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:AdminCountry:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Country', new Country(), new CountryType($this->getInstance()), 'admin_country');
    }

    /**
     * Displays a form to edit an existing Country document.
     *
     * @Route("/{id}/edit", name="admin_country_edit")
     * @Template()
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('Country', $id, new CountryType($this->getInstance()));
    }

    /**
     * Edits an existing Country document.
     *
     * @Route("/{id}/update", name="admin_country_update")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:AdminCountry:edit.html.twig")
     * 
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('Country', $id, new CountryType($this->getInstance()), 'admin_country');
    }

    /**
     * Deletes a Country document.
     *
     * @Route("/{id}/delete", name="admin_country_delete")
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
        return $this->baseDelete('Country', $id, 'admin_country');
    }

}