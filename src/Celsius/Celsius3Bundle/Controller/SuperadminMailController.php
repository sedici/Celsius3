<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\MailTemplate;
use Celsius\Celsius3Bundle\Form\Type\MailTemplateType;
use Celsius\Celsius3Bundle\Filter\Type\MailTemplateFilterType;

/**
 * Order controller.
 *
 * @Route("/superadmin/mail")
 */
class SuperadminMailController extends BaseInstanceDependentController
{

    /**
     * Lists all Templates Mail.
     *
     * @Route("/", name="superadmin_mails")
     * @Template()
     *
     * @return array
     */
    public function indexAction() {
        return $this->baseIndex('MailTemplate', $this->createForm(new MailTemplateFilterType()));
    }
    
    /**
     * Displays a form to create a new mail template.
     *
     * @Route("/new", name="superadmin_mails_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('MailTemplate', new MailTemplate(), new MailTemplateType($this->getInstance()));
    }
    
    /**
     * Displays a form to edit an existing mail template.
     *
     * @Route("/{id}/edit", name="superadmin_mails_edit")
     * @Template()
     *
     * @param string $id The mail template ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id) {
        return $this->baseEdit('MailTemplate', $id, new MailTemplateType($this->getInstance()));
    }
    
    /**
     * Creates a new Mail Document.
     *
     * @Route("/create", name="superadmin_mails_create")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:SuperadminMail:new.html.twig")
     *
     * @return array
     */
    public function createAction() {
        return $this->baseCreate('MailTemplate', new MailTemplate(), new MailTemplateType($this->getInstance()), 'superadmin_mails');
    }
    
    /**
     * Edits an existing Mail TEmplate.
     *
     * @Route("/{id}/update", name="superadmin_mails_update")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:SuperadminMail:edit.html.twig")
     * 
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id) {
        return $this->baseUpdate('MailTemplate', $id, new MailTemplateType($this->getInstance()), 'superadmin_mails');
    }
    
    /**
     * Deletes a Mails Template
     *
     * @Route("/{id}/delete", name="superadmin_mails_delete")
     * @Method("post")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function deleteAction($id) {
        return $this->baseDelete('MailTemplate', $id, 'superadmin_mails');
    }
}