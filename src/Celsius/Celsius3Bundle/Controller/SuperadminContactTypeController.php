<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\ContactType;
use Celsius\Celsius3Bundle\Form\Type\ContactTypeType;

/**
 * ContactType controller.
 *
 * @Route("/superadmin/contacttype")
 */
class SuperadminContactTypeController extends BaseController
{

    /**
     * Lists all ContactType documents.
     *
     * @Route("/", name="superadmin_contacttype")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('ContactType');
    }

    /**
     * Displays a form to create a new ContactType document.
     *
     * @Route("/new", name="superadmin_contacttype_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('ContactType', new ContactType(), new ContactTypeType());
    }

    /**
     * Creates a new ContactType document.
     *
     * @Route("/create", name="superadmin_contacttype_create")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:SuperadminContactType:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('ContactType', new ContactType(), new ContactTypeType(), 'superadmin_contacttype');
    }

    /**
     * Displays a form to edit an existing ContactType document.
     *
     * @Route("/{id}/edit", name="superadmin_contacttype_edit")
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
        return $this->baseEdit('ContactType', $id, new ContactTypeType());
    }

    /**
     * Edits an existing ContactType document.
     *
     * @Route("/{id}/update", name="superadmin_contacttype_update")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:SuperadminContactType:edit.html.twig")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('ContactType', $id, new ContactTypeType(), 'superadmin_contacttype');
    }

    /**
     * Deletes a ContactType document.
     *
     * @Route("/{id}/delete", name="superadmin_contacttype_delete")
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
        return $this->baseDelete('ContactType', $id, 'superadmin_contacttype');
    }

}
