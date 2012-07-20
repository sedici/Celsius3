<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\Location;
use Celsius\Celsius3Bundle\Form\Type\LocationType;

/**
 * Location controller.
 *
 * @Route("/location")
 */
class LocationController extends BaseController
{

    /**
     * Lists all Location documents.
     *
     * @Route("/", name="location")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        $dm = $this->getDocumentManager();

        $documents = $dm->getRepository('CelsiusCelsius3Bundle:Location')->findAll();

        return array('documents' => $documents);
    }

    /**
     * Finds and displays a Location document.
     *
     * @Route("/{id}/show", name="location_show")
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

        $document = $dm->getRepository('CelsiusCelsius3Bundle:Location')->find($id);

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find Location document.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'document' => $document,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Location document.
     *
     * @Route("/new", name="location_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        $document = new Location();
        $form = $this->createForm(new LocationType(), $document);

        return array(
            'document' => $document,
            'form' => $form->createView()
        );
    }

    /**
     * Creates a new Location document.
     *
     * @Route("/create", name="location_create")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:Location:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        $document = new Location();
        $request = $this->getRequest();
        $form = $this->createForm(new LocationType(), $document);
        $form->bindRequest($request);

        if ($form->isValid())
        {
            $dm = $this->getDocumentManager();
            $dm->persist($document);
            $dm->flush();

            return $this->redirect($this->generateUrl('location_show', array('id' => $document->getId())));
        }

        return array(
            'document' => $document,
            'form' => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Location document.
     *
     * @Route("/{id}/edit", name="location_edit")
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

        $document = $dm->getRepository('CelsiusCelsius3Bundle:Location')->find($id);

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find Location document.');
        }

        $editForm = $this->createForm(new LocationType(), $document);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'document' => $document,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Location document.
     *
     * @Route("/{id}/update", name="location_update")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:Location:edit.html.twig")
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

        $document = $dm->getRepository('CelsiusCelsius3Bundle:Location')->find($id);

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find Location document.');
        }

        $editForm = $this->createForm(new LocationType(), $document);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid())
        {
            $dm->persist($document);
            $dm->flush();

            return $this->redirect($this->generateUrl('location_edit', array('id' => $id)));
        }

        return array(
            'document' => $document,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Location document.
     *
     * @Route("/{id}/delete", name="location_delete")
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
            $document = $dm->getRepository('CelsiusCelsius3Bundle:Location')->find($id);

            if (!$document)
            {
                throw $this->createNotFoundException('Unable to find Location document.');
            }

            $dm->remove($document);
            $dm->flush();
        }

        return $this->redirect($this->generateUrl('location'));
    }

}
