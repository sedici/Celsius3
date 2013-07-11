<?php

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Document\Contact;
use Celsius3\CoreBundle\Form\Type\SuperadminContactType;

/**
 * Contact controller.
 *
 * @Route("/superadmin/contact")
 */
class SuperadminContactController extends BaseController
{

    /**
     * Lists all Contact documents.
     *
     * @Route("/", name="superadmin_contact")
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
     * @Route("/{id}/show", name="superadmin_contact_show")
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
     * @Route("/new", name="superadmin_contact_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('Contact', new Contact(), new SuperadminContactType());
    }

    /**
     * Creates a new Contact document.
     *
     * @Route("/create", name="superadmin_contact_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:Contact:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Contact', new Contact(), new SuperadminContactType(), 'superadmin_contact');
    }

    /**
     * Displays a form to edit an existing Contact document.
     *
     * @Route("/{id}/edit", name="superadmin_contact_edit")
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
        return $this->baseEdit('Contact', $id, new SuperadminContactType());
    }

    /**
     * Edits an existing Contact document.
     *
     * @Route("/{id}/update", name="superadmin_contact_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:Contact:edit.html.twig")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('Contact', $id, new SuperadminContactType(), 'superadmin_contact');
    }

    /**
     * Deletes a Contact document.
     *
     * @Route("/{id}/delete", name="superadmin_contact_delete")
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
        return $this->baseDelete('Contact', $id, 'superadmin_contact');
    }

}
