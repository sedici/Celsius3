<?php

namespace Celsius\Celsius3Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
 * @Route("/superadmin/user")
 */
class SuperadminBaseUserController extends BaseController
{

    /**
     * Lists all BaseUser documents.
     *
     * @Route("/", name="superadmin_user")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('BaseUser', $this->createForm(new BaseUserFilterType()));
    }

    /**
     * Displays a form to create a new BaseUser document.
     *
     * @Route("/new", name="superadmin_user_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('BaseUser', new BaseUser(), new BaseUserType());
    }

    /**
     * Creates a new BaseUser document.
     *
     * @Route("/create", name="superadmin_user_create")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:SuperadminBaseUser:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('BaseUser', new BaseUser(), new BaseUserType(), 'superadmin_user');
    }

    /**
     * Displays a form to edit an existing BaseUser document.
     *
     * @Route("/{id}/edit", name="superadmin_user_edit")
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
        return $this->baseEdit('BaseUser', $id, new BaseUserType());
    }

    /**
     * Edits an existing BaseUser document.
     *
     * @Route("/{id}/update", name="superadmin_user_update")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:SuperadminBaseUser:edit.html.twig")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('BaseUser', $id, new BaseUserType(), 'superadmin_user');
    }

    /**
     * Deletes a BaseUser document.
     *
     * @Route("/{id}/delete", name="superadmin_user_delete")
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
        return $this->baseDelete('BaseUser', $id, 'superadmin_user');
    }

    /**
     * Displays a form to transform an existing BaseUser document.
     *
     * @Route("/{id}/transform", name="superadmin_user_transform")
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
        $document = $this->findQuery('BaseUser', $id);

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find User.');
        }

        $transformForm = $this->createForm(new UserTransformType(), array(
            'type' => $this->get('user_manager')->getCurrentRole($document),
        ));

        return array(
            'document' => $document,
            'transform_form' => $transformForm->createView(),
            'route' => null,
        );
    }

    /**
     * Transforms an existing BaseUser document.
     *
     * @Route("/{id}/dotransform", name="superadmin_user_do_transform")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:SuperadminBaseUser:transform.html.twig")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function doTransformAction($id)
    {
        $document = $this->findQuery('BaseUser', $id);

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find User.');
        }

        $transformForm = $this->createForm(new UserTransformType());

        $request = $this->getRequest();

        $transformForm->bind($request);

        if ($transformForm->isValid())
        {
            $data = $transformForm->getData();
            $document = $this->get('user_manager')->transform($data['type'], $document);
            $dm = $this->getDocumentManager();
            $dm->persist($document);
            $dm->flush();

            $this->get('session')->getFlashBag()->add('success', 'The User was successfully transformed.');

            return $this->redirect($this->generateUrl('superadmin_user_transform', array('id' => $id)));
        }

        $this->get('session')->getFlashBag()->add('error', 'There were errors transforming the User.');

        return array(
            'document' => $document,
            'edit_form' => $transformForm->createView(),
        );
    }

}
