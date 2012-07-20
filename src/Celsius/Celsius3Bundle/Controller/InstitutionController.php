<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\Institution;
use Celsius\Celsius3Bundle\Form\Type\InstitutionType;

/**
 * Institution controller.
 *
 * @Route("/institution")
 */
class InstitutionController extends BaseController
{

    /**
     * Lists all Institution documents.
     *
     * @Route("/", name="institution")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        $dm = $this->getDocumentManager();

        $documents = $dm->getRepository('CelsiusCelsius3Bundle:Institution')->findAll();

        return array('documents' => $documents);
    }

    /**
     * Finds and displays a Institution document.
     *
     * @Route("/{id}/show", name="institution_show")
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
        $dm = $this->getDocumentManager();

        $document = $dm->getRepository('CelsiusCelsius3Bundle:Institution')->find($id);

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find Institution document.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'document' => $document,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Institution document.
     *
     * @Route("/new", name="institution_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        $document = new Institution();
        $form = $this->createForm(new InstitutionType(), $document);

        return array(
            'document' => $document,
            'form' => $form->createView()
        );
    }

    /**
     * Creates a new Institution document.
     *
     * @Route("/create", name="institution_create")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:Institution:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        $document = new Institution();
        $request = $this->getRequest();
        $form = $this->createForm(new InstitutionType(), $document);
        $form->bindRequest($request);

        if ($form->isValid())
        {
            $dm = $this->getDocumentManager();
            $dm->persist($document);
            $dm->flush();

            return $this->redirect($this->generateUrl('institution_show', array('id' => $document->getId())));
        }

        return array(
            'document' => $document,
            'form' => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Institution document.
     *
     * @Route("/{id}/edit", name="institution_edit")
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
        $dm = $this->getDocumentManager();

        $document = $dm->getRepository('CelsiusCelsius3Bundle:Institution')->find($id);

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find Institution document.');
        }

        $editForm = $this->createForm(new InstitutionType(), $document);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'document' => $document,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Institution document.
     *
     * @Route("/{id}/update", name="institution_update")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:Institution:edit.html.twig")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        $dm = $this->getDocumentManager();

        $document = $dm->getRepository('CelsiusCelsius3Bundle:Institution')->find($id);

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find Institution document.');
        }

        $editForm = $this->createForm(new InstitutionType(), $document);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid())
        {
            $dm->persist($document);
            $dm->flush();

            return $this->redirect($this->generateUrl('institution_edit', array('id' => $id)));
        }

        return array(
            'document' => $document,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Institution document.
     *
     * @Route("/{id}/delete", name="institution_delete")
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
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid())
        {
            $dm = $this->getDocumentManager();
            $document = $dm->getRepository('CelsiusCelsius3Bundle:Institution')->find($id);

            if (!$document)
            {
                throw $this->createNotFoundException('Unable to find Institution document.');
            }

            $dm->remove($document);
            $dm->flush();
        }

        return $this->redirect($this->generateUrl('institution'));
    }

}
