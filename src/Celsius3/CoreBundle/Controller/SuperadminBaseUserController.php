<?php

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Document\BaseUser;
use Celsius3\CoreBundle\Form\Type\BaseUserType;
use Celsius3\CoreBundle\Form\Type\UserTransformType;
use Celsius3\CoreBundle\Filter\Type\BaseUserFilterType;

/**
 * BaseUser controller.
 *
 * @Route("/superadmin/user")
 */
class SuperadminBaseUserController extends BaseUserController
{

    protected function listQuery($name)
    {
        return $this->getDocumentManager()
                        ->getRepository('Celsius3CoreBundle:' . $name)
                        ->createQueryBuilder();
    }

    protected function findQuery($name, $id)
    {
        return $this->getDocumentManager()
                        ->getRepository('Celsius3CoreBundle:' . $name)->find($id);
    }

    protected function getResultsPerPage()
    {
        return $this->container->getParameter('max_per_page');
    }

    protected function filter($name, $filter_form, $query)
    {
        return $this->get('celsius3_core.filter_manager')
                        ->filter($query, $filter_form, 'Celsius3\\CoreBundle\\Document\\' . $name);
    }

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
        return $this->baseNew('BaseUser', new BaseUser(), new BaseUserType($this->container, 'Celsius3\CoreBundle\Document\BaseUser', $this->getDirectory()));
    }

    /**
     * Creates a new BaseUser document.
     *
     * @Route("/create", name="superadmin_user_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminBaseUser:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('BaseUser', new BaseUser(), new BaseUserType($this->container, 'Celsius3\CoreBundle\Document\BaseUser', $this->getDirectory()), 'superadmin_user');
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
        return $this->baseEdit('BaseUser', $id, new BaseUserType($this->container, 'Celsius3\CoreBundle\Document\BaseUser', $this->getDirectory(), true));
    }

    /**
     * Edits an existing BaseUser document.
     *
     * @Route("/{id}/update", name="superadmin_user_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminBaseUser:edit.html.twig")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('BaseUser', $id, new BaseUserType($this->container, 'Celsius3\CoreBundle\Document\BaseUser', $this->getDirectory(), true), 'superadmin_user');
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
        return $this->baseTransformAction($id, new UserTransformType());
    }

    /**
     * Transforms an existing BaseUser document.
     *
     * @Route("/{id}/dotransform", name="superadmin_user_do_transform")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminBaseUser:transform.html.twig")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function doTransformAction($id)
    {
        return $this->baseDoTransformAction($id, new UserTransformType(), 'superadmin_user');
    }

    /**
     * Enables a BaseUser document.
     *
     * @Route("/{id}/enable", name="superadmin_user_enable")
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

}
