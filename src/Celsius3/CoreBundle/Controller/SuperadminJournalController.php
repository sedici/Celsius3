<?php

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Document\Journal;
use Celsius3\CoreBundle\Form\Type\JournalType;
use Celsius3\CoreBundle\Filter\Type\JournalFilterType;

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
        return $this->baseIndex('Journal', $this->createForm(new JournalFilterType()));
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
        return $this->baseNew('Journal', new Journal(), new JournalType($this->getDirectory()));
    }

    /**
     * Creates a new Journal document.
     *
     * @Route("/create", name="superadmin_journal_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminJournal:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Journal', new Journal(), new JournalType($this->getDirectory()), 'superadmin_journal');
    }

    /**
     * Displays a form to edit an existing Journal document.
     *
     * @Route("/{id}/edit", name="superadmin_journal_edit")
     * @Template()
     *
     * @param string $id
     *                   The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('Journal', $id, new JournalType($this->getDirectory()));
    }

    /**
     * Edits an existing Journal document.
     *
     * @Route("/{id}/update", name="superadmin_journal_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminJournal:edit.html.twig")
     *
     * @param string $id
     *                   The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('Journal', $id, new JournalType($this->getDirectory()), 'superadmin_journal');
    }

    /**
     * Deletes a Journal document.
     *
     * @Route("/{id}/delete", name="superadmin_journal_delete")
     * @Method("post")
     *
     * @param string $id
     *                   The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function deleteAction($id)
    {
        return $this->baseDelete('Journal', $id, 'superadmin_journal');
    }

    /**
     * Batch actions.
     *
     * @Route("/batch", name="superadmin_journal_batch")
     *
     * @return array
     */
    public function batchAction()
    {
        return $this->baseBatch();
    }

    protected function batchUnion($element_ids)
    {
        return $this->render('Celsius3CoreBundle:SuperadminJournal:batchUnion.html.twig', $this->baseUnion('Journal', $element_ids));
    }

    /**
     * Unifies a group of Journal documents.
     *
     * @Route("/doUnion", name="superadmin_journal_doUnion")
     * @Method("post")
     *
     * @return array
     */
    public function doUnionAction()
    {
        $element_ids = $this->getRequest()->request->get('element');
        $main_id = $this->getRequest()->request->get('main');

        return $this->baseDoUnion('Journal', $element_ids, $main_id, 'superadmin_journal');
    }

}
