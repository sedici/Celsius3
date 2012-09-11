<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\Journal;
use Celsius\Celsius3Bundle\Form\Type\JournalType;

/**
 * Location controller.
 *
 * @Route("/superadmin/journal")
 */
class SuperadminJournalController extends BaseController
{

    /**
     * Lists all Journal documents.
     *
     * @Route("/", name="superadmin_journal")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('Journal');
    }

    /**
     * Displays a form to create a new Journal document.
     *
     * @Route("/new", name="superadmin_journal_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('Journal', new Journal(), new JournalType());
    }

    /**
     * Creates a new Journal document.
     *
     * @Route("/create", name="superadmin_journal_create")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:SuperadminJournal:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Journal', new Journal(), new JournalType(), 'superadmin_journal');
    }

    /**
     * Displays a form to edit an existing Journal document.
     *
     * @Route("/{id}/edit", name="superadmin_journal_edit")
     * @Template()
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('Journal', $id, new JournalType());
    }

    /**
     * Edits an existing Journal document.
     *
     * @Route("/{id}/update", name="superadmin_journal_update")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:SuperadminJournal:edit.html.twig")
     * 
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('Journal', $id, new JournalType(), 'superadmin_journal');
    }

    /**
     * Deletes a Journal document.
     *
     * @Route("/{id}/delete", name="superadmin_journal_delete")
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
        return $this->baseDelete('Journal', $id, 'superadmin_journal');
    }

}
