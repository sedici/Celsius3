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
 * @Route("/admin/journal")
 */
class AdminJournalController extends BaseInstanceDependentController
{

    /**
     * Lists all Journal documents.
     *
     * @Route("/", name="admin_journal")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this
                ->baseIndex('Journal',
                        $this
                                ->createForm(
                                        new JournalFilterType(
                                                $this->getInstance())));
    }

    /**
     * Displays a form to create a new Journal document.
     *
     * @Route("/new", name="admin_journal_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this
                ->baseNew('Journal', new Journal(),
                        new JournalType($this->getInstance()));
    }

    /**
     * Creates a new Journal document.
     *
     * @Route("/create", name="admin_journal_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminJournal:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this
                ->baseCreate('Journal', new Journal(),
                        new JournalType($this->getInstance()), 'admin_journal');
    }

    /**
     * Displays a form to edit an existing Journal document.
     *
     * @Route("/{id}/edit", name="admin_journal_edit")
     * @Template()
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id)
    {
        return $this
                ->baseEdit('Journal', $id,
                        new JournalType($this->getInstance()));
    }

    /**
     * Edits an existing Journal document.
     *
     * @Route("/{id}/update", name="admin_journal_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminJournal:edit.html.twig")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        return $this
                ->baseUpdate('Journal', $id,
                        new JournalType($this->getInstance()), 'admin_journal');
    }

    /**
     * Deletes a Journal document.
     *
     * @Route("/{id}/delete", name="admin_journal_delete")
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
        return $this->baseDelete('Journal', $id, 'admin_journal');
    }

}
