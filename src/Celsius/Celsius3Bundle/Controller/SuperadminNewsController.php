<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\News;
use Celsius\Celsius3Bundle\Form\Type\NewsType;

/**
 * News controller.
 *
 * @Route("/superadmin/news")
 */
class SuperadminNewsController extends BaseController {

    protected function listQuery($name) {
        return $this->getDocumentManager()
                        ->getRepository('CelsiusCelsius3Bundle:' . $name)
                        ->createQueryBuilder()
                        ->field('instance.id')->equals(null);
        ;
    }

    /**
     * Lists all News documents.
     *
     * @Route("/", name="superadmin_news")
     * @Template()
     *
     * @return array
     */
    public function indexAction() {
        return $this->baseIndex('News');
    }

    /**
     * Finds and displays a News document.
     *
     * @Route("/{id}/show", name="superadmin_news_show")
     * @Template()
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function showAction($id) {
        return $this->baseShow('News', $id);
    }

    /**
     * Displays a form to create a new News document.
     *
     * @Route("/new", name="superadmin_news_new")
     * @Template()
     *
     * @return array
     */
    public function newAction() {
        return $this->baseNew('News', new News(), new NewsType());
    }

    /**
     * Creates a new News document.
     *
     * @Route("/create", name="superadmin_news_create")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:SuperadminNews:new.html.twig")
     *
     * @return array
     */
    public function createAction() {
        return $this->baseCreate('News', new News(), new NewsType(), 'superadmin_news');
    }

    /**
     * Displays a form to edit an existing News document.
     *
     * @Route("/{id}/edit", name="superadmin_news_edit")
     * @Template()
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id) {
        return $this->baseEdit('News', $id, new NewsType());
    }

    /**
     * Edits an existing News document.
     *
     * @Route("/{id}/update", name="superadmin_news_update")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:SuperadminNews:edit.html.twig")
     * 
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id) {
        return $this->baseUpdate('News', $id, new NewsType(), 'superadmin_news');
    }

    /**
     * Deletes a News document.
     *
     * @Route("/{id}/delete", name="superadmin_news_delete")
     * @Method("post")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function deleteAction($id) {
        return $this->baseDelete('News', $id, 'superadmin_news');
    }

}
