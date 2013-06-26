<?php

namespace Celsius3\CoreBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Document\CustomUserField;
use Celsius3\CoreBundle\Form\Type\CustomUserFieldType;
use Celsius3\CoreBundle\Filter\Type\CustomUserFieldFilterType;

/**
 * Order controller.
 *
 * @Route("/admin/customuserfield")
 */
class AdminCustomUserFieldController extends BaseInstanceDependentController
{

    /**
     * Lists all CustomUserField documents.
     *
     * @Route("/", name="admin_customuserfield")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this
                ->baseIndex('CustomUserField',
                        $this
                                ->createForm(
                                        new CustomUserFieldFilterType(
                                                $this->getInstance())));
    }

    /**
     * Displays a form to create a new CustomUserField document.
     *
     * @Route("/new", name="admin_customuserfield_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this
                ->baseNew('CustomUserField', new CustomUserField(),
                        new CustomUserFieldType($this->getInstance()));
    }

    /**
     * Creates a new CustomUserField document.
     *
     * @Route("/create", name="admin_customuserfield_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminCustomUserField:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this
                ->baseCreate('CustomUserField', new CustomUserField(),
                        new CustomUserFieldType($this->getInstance()),
                        'admin_customuserfield');
    }

    /**
     * Displays a form to edit an existing CustomUserField document.
     *
     * @Route("/{id}/edit", name="admin_customuserfield_edit")
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
                ->baseEdit('CustomUserField', $id,
                        new CustomUserFieldType($this->getInstance()));
    }

    /**
     * Edits an existing CustomUserField document.
     *
     * @Route("/{id}/update", name="admin_customuserfield_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminCustomUserField:edit.html.twig")
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
                ->baseUpdate('CustomUserField', $id,
                        new CustomUserFieldType($this->getInstance()),
                        'admin_customuserfield');
    }

    /**
     * Deletes a CustomUserField document.
     *
     * @Route("/{id}/delete", name="admin_customuserfield_delete")
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
        return $this
                ->baseDelete('CustomUserField', $id, 'admin_customuserfield');
    }

}