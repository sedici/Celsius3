<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\BaseUser;
use Celsius\Celsius3Bundle\Form\Type\BaseUserType;
use Celsius\Celsius3Bundle\Form\Type\UserTransformType;
use Celsius\Celsius3Bundle\Filter\Type\BaseUserFilterType;

/**
 * BaseUser controller.
 *
 * @Route("/admin/user")
 */
class AdminBaseUserController extends BaseUserController
{

    /**
     * Lists all BaseUser documents.
     *
     * @Route("/", name="admin_user")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('BaseUser', $this->createForm(new BaseUserFilterType($this->getInstance())));
    }

    /**
     * Displays a form to create a new BaseUser document.
     *
     * @Route("/new", name="admin_user_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('BaseUser', new BaseUser(), new BaseUserType($this->getDocumentManager(), $this->getInstance()));
    }

    /**
     * Creates a new BaseUser document.
     *
     * @Route("/create", name="admin_user_create")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:AdminBaseUser:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        $request = $this->getRequest();
        $document = new BaseUser();
        $form = $this->createForm(new BaseUserType($this->getDocumentManager(), $this->getInstance()), $document);
        $form->bind($request);

        if ($form->isValid())
        {
            $dm = $this->getDocumentManager();
            $dm->persist($document);
            $dm->flush();

            $this->get('custom_field_helper')->processCustomFields($this->getInstance(), $form, $document);

            $this->get('session')->getFlashBag()->add('success', 'The BaseUser was successfully created.');

            return $this->redirect($this->generateUrl('admin_user'));
        }

        $this->get('session')->getFlashBag()->add('error', 'There were errors creating the BaseUser.');

        return array(
            'document' => $document,
            'form' => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing BaseUser document.
     *
     * @Route("/{id}/edit", name="admin_user_edit")
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
        return $this->baseEdit('BaseUser', $id, new BaseUserType($this->getDocumentManager(), $this->getInstance()));
    }

    /**
     * Edits an existing BaseUser document.
     *
     * @Route("/{id}/update", name="admin_user_update")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:AdminBaseUser:edit.html.twig")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        $document = $this->findQuery('BaseUser', $id);

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find BaseUser.');
        }

        $editForm = $this->createForm(new BaseUserType($this->getDocumentManager(), $this->getInstance()), $document);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid())
        {
            $dm = $this->getDocumentManager();
            $dm->persist($document);
            $dm->flush();

            $this->get('custom_field_helper')->processCustomFields($this->getInstance(), $editForm, $document);

            $this->get('session')->getFlashBag()->add('success', 'The BaseUser was successfully edited.');

            return $this->redirect($this->generateUrl('admin_user_edit', array('id' => $id)));
        }

        $this->get('session')->getFlashBag()->add('error', 'There were errors editing the BaseUser.');

        return array(
            'document' => $document,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a BaseUser document.
     *
     * @Route("/{id}/delete", name="admin_user_delete")
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
        return $this->baseDelete('BaseUser', $id, 'admin_user');
    }

    /**
     * Displays a form to transform an existing BaseUser document.
     *
     * @Route("/{id}/transform", name="admin_user_transform")
     * @Template()
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function transformAction($id)
    {
        return $this->baseTransformAction($id, new UserTransformType($this->getInstance()));
    }

    /**
     * Transforms an existing BaseUser document.
     *
     * @Route("/{id}/dotransform", name="admin_user_do_transform")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:AdminBaseUser:transform.html.twig")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function doTransformAction($id)
    {
        return $this->baseDoTransformAction($id, new UserTransformType($this->getInstance()), 'admin_user');
    }

    /**
     * Enables a BaseUser document.
     *
     * @Route("/{id}/enable", name="admin_user_enable")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function enableAction($id)
    {
        return $this->baseEnableAction($id);
    }

    /**
     * Displays a list to unify a group of BaseUser documents.
     *
     * @Route("/union", name="admin_user_union")
     * @Method("post")
     * @Template()
     *
     * @return array
     */
    public function unionAction()
    {
        $element_ids = $this->getRequest()->request->get('element');
        return $this->baseUnion('BaseUser', $element_ids);
    }

    /**
     * Unifies a group of Journal documents.
     *
     * @Route("/doUnion", name="admin_user_doUnion")
     * @Method("post")
     *
     * @return array
     */
    public function doUnionAction()
    {
        $element_ids = $this->getRequest()->request->get('element');
        $main_id = $this->getRequest()->request->get('main');
        return $this->baseDoUnion('BaseUser', $element_ids, $main_id, 'admin_user', false);
    }

}
