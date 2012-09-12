<?php

namespace Celsius\Celsius3Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\BaseUser;
use Celsius\Celsius3Bundle\Form\Type\BaseUserType;
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
        return $this->baseIndex('BaseUser');
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

}
