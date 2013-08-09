<?php

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Document\Catalog;
use Celsius3\CoreBundle\Form\Type\CatalogType;
use Celsius3\CoreBundle\Filter\Type\CatalogFilterType;

/**
 * Location controller.
 *
 * @Route("/superadmin/catalog")
 */
class SuperadminCatalogController extends BaseController
{

    /**
     * Lists all Catalog documents.
     *
     * @Route("/", name="superadmin_catalog")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('Catalog', $this->createForm(new CatalogFilterType()));
    }

    /**
     * Displays a form to create a new Catalog document.
     *
     * @Route("/new", name="superadmin_catalog_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('Catalog', new Catalog(), new CatalogType($this->getDocumentManager(), $this->getDirectory()));
    }

    /**
     * Creates a new Catalog document.
     *
     * @Route("/create", name="superadmin_catalog_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminCatalog:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Catalog', new Catalog(), new CatalogType($this->getDocumentManager(), $this->getDirectory()), 'superadmin_catalog');
    }

    /**
     * Displays a form to edit an existing Catalog document.
     *
     * @Route("/{id}/edit", name="superadmin_catalog_edit")
     * @Template()
     *
     * @param string $id
     *            The document ID
     *            
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('Catalog', $id, new CatalogType($this->getDocumentManager(), $this->getDirectory()));
    }

    /**
     * Edits an existing Catalog document.
     *
     * @Route("/{id}/update", name="superadmin_catalog_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminCatalog:edit.html.twig")
     *
     * @param string $id
     *            The document ID
     *            
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('Catalog', $id, new CatalogType($this->getDocumentManager(), $this->getDirectory()), 'superadmin_catalog');
    }

    /**
     * Deletes a Catalog document.
     *
     * @Route("/{id}/delete", name="superadmin_catalog_delete")
     * @Method("post")
     *
     * @param string $id
     *            The document ID
     *            
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function deleteAction($id)
    {
        return $this->baseDelete('Catalog', $id, 'superadmin_catalog');
    }

    /**
     * Batch actions.
     *
     * @Route("/batch", name="superadmin_catalog_batch")
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function batchAction()
    {
        return $this->baseBatch();
    }

    protected function batchUnion($element_ids)
    {
        return $this->render('Celsius3CoreBundle:SuperadminCatalog:batchUnion.html.twig', $this->baseUnion('Catalog', $element_ids));
    }

    /**
     * Unifies a group of Catalog documents.
     *
     * @Route("/doUnion", name="superadmin_catalog_doUnion")
     * @Method("post")
     *
     * @return array
     */
    public function doUnionAction()
    {
        $element_ids = $this->getRequest()->request->get('element');
        $main_id = $this->getRequest()->request->get('main');
        return $this->baseDoUnion('Catalog', $element_ids, $main_id, 'superadmin_catalog');
    }

}
