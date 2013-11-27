<?php

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Document\Hive;
use Celsius3\CoreBundle\Form\Type\HiveType;

/**
 * Hive controller.
 *
 * @Route("/superadmin/hive")
 */
class SuperadminHiveController extends BaseController
{

    /**
     * Lists all Hive documents.
     *
     * @Route("/", name="superadmin_hive")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('Hive');
    }

    /**
     * Displays a form to create a new Hive document.
     *
     * @Route("/new", name="superadmin_hive_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('Hive', new Hive(), new HiveType());
    }

    /**
     * Creates a new Hive document.
     *
     * @Route("/create", name="superadmin_hive_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminHive:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Hive', new Hive(), new HiveType(), 'superadmin_hive');
    }

    /**
     * Displays a form to edit an existing Hive document.
     *
     * @Route("/{id}/edit", name="superadmin_hive_edit")
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
        return $this->baseEdit('Hive', $id, new HiveType());
    }

    /**
     * Edits an existing Hive document.
     *
     * @Route("/{id}/update", name="superadmin_hive_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminHive:edit.html.twig")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('Hive', $id, new HiveType(), 'superadmin_hive');
    }

    /**
     * Deletes a Hive document.
     *
     * @Route("/{id}/delete", name="superadmin_hive_delete")
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
        return $this->baseDelete('Hive', $id, 'superadmin_hive');
    }

}
