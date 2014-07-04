<?php

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Document\Institution;
use Celsius3\CoreBundle\Form\Type\InstitutionType;
use Celsius3\CoreBundle\Filter\Type\InstitutionFilterType;

/**
 * Location controller.
 *
 * @Route("/superadmin/institution")
 */
class SuperadminInstitutionController extends BaseController
{

    /**
     * Lists all Institution documents.
     *
     * @Route("/", name="superadmin_institution")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('Institution', $this->createForm(new InstitutionFilterType()));
    }

    /**
     * Displays a form to create a new Institution document.
     *
     * @Route("/new", name="superadmin_institution_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('Institution', new Institution(), new InstitutionType($this->getDocumentManager(), $this->getDirectory()));
    }

    /**
     * Creates a new Institution document.
     *
     * @Route("/create", name="superadmin_institution_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminInstitution:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Institution', new Institution(), new InstitutionType($this->getDocumentManager(), $this->getDirectory()), 'superadmin_institution');
    }

    /**
     * Displays a form to edit an existing Institution document.
     *
     * @Route("/{id}/edit", name="superadmin_institution_edit")
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
        return $this->baseEdit('Institution', $id, new InstitutionType($this->getDocumentManager(), $this->getDirectory()));
    }

    /**
     * Edits an existing Institution document.
     *
     * @Route("/{id}/update", name="superadmin_institution_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminInstitution:edit.html.twig")
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
        return $this->baseUpdate('Institution', $id, new InstitutionType($this->getDocumentManager(), $this->getDirectory()), 'superadmin_institution');
    }

    /**
     * Deletes a Institution document.
     *
     * @Route("/{id}/delete", name="superadmin_institution_delete")
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
        return $this->baseDelete('Institution', $id, 'superadmin_institution');
    }

    /**
     * Batch actions.
     *
     * @Route("/batch", name="superadmin_institution_batch")
     *
     * @return array
     */
    public function batchAction()
    {
        return $this->baseBatch();
    }

    protected function batchUnion($element_ids)
    {
        return $this->render('Celsius3CoreBundle:SuperadminInstitution:batchUnion.html.twig', $this->baseUnion('Institution', $element_ids));
    }

    /**
     * Unifies a group of Institution documents.
     *
     * @Route("/doUnion", name="superadmin_institution_doUnion")
     * @Method("post")
     *
     * @return array
     */
    public function doUnionAction()
    {
        $element_ids = $this->getRequest()->request->get('element');
        $main_id = $this->getRequest()->request->get('main');

        return $this->baseDoUnion('Institution', $element_ids, $main_id, 'superadmin_institution');
    }

}
