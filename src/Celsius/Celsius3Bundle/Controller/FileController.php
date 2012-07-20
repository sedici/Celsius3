<?php

namespace Celsius\Celsius3Bundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\File;
use Celsius\Celsius3Bundle\Form\Type\FileType;

/**
 * File controller.
 *
 * @Route("/admin/file")
 */
class FileController extends BaseController
{

    /**
     * Lists all File documents.
     *
     * @Route("/", name="file")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('File');
    }

    /**
     * Finds and displays a File document.
     *
     * @Route("/{id}/show", name="file_show")
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
        return $this->baseShow('File', $id);
    }

    /**
     * Displays a form to create a new File document.
     *
     * @Route("/new", name="file_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('File', new File(), new FileType());
    }

    /**
     * Creates a new File document.
     *
     * @Route("/create", name="file_create")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:File:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('File', new File(), new FileType(), 'file');
    }

    /**
     * Displays a form to edit an existing File document.
     *
     * @Route("/{id}/edit", name="file_edit")
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
        return $this->baseEdit('File', $id, new FileType());
    }

    /**
     * Edits an existing File document.
     *
     * @Route("/{id}/update", name="file_update")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:File:edit.html.twig")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('File', $id, new FileType(), 'file');
    }

    /**
     * Deletes a File document.
     *
     * @Route("/{id}/delete", name="file_delete")
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
        return $this->baseDelete('File', $id, 'file');
    }

    /**
     * Downloads the file associated to a File document.
     *
     * @Route("/{id}/download", name="file_download")
     *
     * @param string $id The document ID
     */
    public function downloadAction($id)
    {
        $document = $this->getDocumentManager()->getRepository('CelsiusCelsius3Bundle:File')->find($id);

        $response = new Response();
        $response->headers->set('Content-type', $document->getMime() . ';');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $document->getName() . '"');
        $response->setContent($document->getFile()->getBytes());

        return $response;
    }

}
