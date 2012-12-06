<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\Contact;
use Celsius\Celsius3Bundle\Form\Type\ContactType;

/**
 * Contact controller.
 *
 * @Route("/admin/contact")
 */
class ContactController extends BaseInstanceDependentController
{

    /**
     * Lists all Contact documents.
     *
     * @Route("/", name="contact")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('Contact');
    }

    /**
     * Finds and displays a Contact document.
     *
     * @Route("/{id}/show", name="contact_show")
     * @Template()
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function showAction($id)
    {
        return $this->baseShow('Contact', $id);
    }

    /**
     * Displays a form to create a new Contact document.
     *
     * @Route("/new", name="contact_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('Contact', new Contact(), new ContactType($this->getInstance()));
    }

    /**
     * Creates a new Contact document.
     *
     * @Route("/create", name="contact_create")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:Contact:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Contact', new Contact(), new ContactType($this->getInstance()), 'contact');
    }

    /**
     * Displays a form to edit an existing Contact document.
     *
     * @Route("/{id}/edit", name="contact_edit")
     * @Template()
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('Contact', $id, new ContactType($this->getInstance()));
    }

    /**
     * Edits an existing Contact document.
     *
     * @Route("/{id}/update", name="contact_update")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:Contact:edit.html.twig")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('Contact', $id, new ContactType($this->getInstance()), 'contact');
    }

    /**
     * Deletes a Contact document.
     *
     * @Route("/{id}/delete", name="contact_delete")
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
        return $this->baseDelete('Contact', $id, 'contact');
    }

}
