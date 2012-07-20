<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\Country;
use Celsius\Celsius3Bundle\Form\Type\CountryType;

/**
 * Country controller.
 *
 * @Route("/country")
 */
class CountryController extends BaseController
{

    /**
     * Lists all Country documents.
     *
     * @Route("/", name="country")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        $dm = $this->getDocumentManager();

        $documents = $dm->getRepository('CelsiusCelsius3Bundle:Country')->findAll();

        return array('documents' => $documents);
    }

    /**
     * Finds and displays a Country document.
     *
     * @Route("/{id}/show", name="country_show")
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

        $document = $dm->getRepository('CelsiusCelsius3Bundle:Country')->find($id);

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find Country document.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'document' => $document,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Country document.
     *
     * @Route("/new", name="country_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        $document = new Country();
        $form = $this->createForm(new CountryType(), $document);

        return array(
            'document' => $document,
            'form' => $form->createView()
        );
    }

    /**
     * Creates a new Country document.
     *
     * @Route("/create", name="country_create")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:Country:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        $document = new Country();
        $request = $this->getRequest();
        $form = $this->createForm(new CountryType(), $document);
        $form->bindRequest($request);

        if ($form->isValid())
        {
            $dm = $this->getDocumentManager();
            $dm->persist($document);
            $dm->flush();

            return $this->redirect($this->generateUrl('country_show', array('id' => $document->getId())));
        }

        return array(
            'document' => $document,
            'form' => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Country document.
     *
     * @Route("/{id}/edit", name="country_edit")
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

        $document = $dm->getRepository('CelsiusCelsius3Bundle:Country')->find($id);

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find Country document.');
        }

        $editForm = $this->createForm(new CountryType(), $document);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'document' => $document,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Country document.
     *
     * @Route("/{id}/update", name="country_update")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:Country:edit.html.twig")
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

        $document = $dm->getRepository('CelsiusCelsius3Bundle:Country')->find($id);

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find Country document.');
        }

        $editForm = $this->createForm(new CountryType(), $document);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid())
        {
            $dm->persist($document);
            $dm->flush();

            return $this->redirect($this->generateUrl('country_edit', array('id' => $id)));
        }

        return array(
            'document' => $document,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Country document.
     *
     * @Route("/{id}/delete", name="country_delete")
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
            $document = $dm->getRepository('CelsiusCelsius3Bundle:Country')->find($id);

            if (!$document)
            {
                throw $this->createNotFoundException('Unable to find Country document.');
            }

            $dm->remove($document);
            $dm->flush();
        }

        return $this->redirect($this->generateUrl('country'));
    }

}
