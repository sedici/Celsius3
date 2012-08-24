<?php

namespace Celsius\Celsius3Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius\Celsius3Bundle\Document\Instance;
use Celsius\Celsius3Bundle\Form\Type\InstanceType;

/**
 * Instance controller.
 *
 * @Route("/superadmin/instance")
 */
class InstanceController extends BaseController
{

    /**
     * Lists all Instance documents.
     *
     * @Route("/", name="instance")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('Instance');
    }

    /**
     * Finds and displays a Instance document.
     *
     * @Route("/{id}/show", name="instance_show")
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
        return $this->baseShow('Instance', $id);
    }

    /**
     * Displays a form to create a new Instance document.
     *
     * @Route("/new", name="instance_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('Instance', new Instance(), new InstanceType());
    }

    /**
     * Creates a new Instance document.
     *
     * @Route("/create", name="instance_create")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:Instance:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Instance', new Instance(), new InstanceType(), 'instance');
    }

    /**
     * Displays a form to edit an existing Instance document.
     *
     * @Route("/{id}/edit", name="instance_edit")
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
        return $this->baseEdit('Instance', $id, new InstanceType());
    }

    /**
     * Edits an existing Instance document.
     *
     * @Route("/{id}/update", name="instance_update")
     * @Method("post")
     * @Template("CelsiusCelsius3Bundle:Instance:edit.html.twig")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('Instance', $id, new InstanceType(), 'instance');
    }

    /**
     * Deletes a Instance document.
     *
     * @Route("/{id}/delete", name="instance_delete")
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
        return $this->baseDelete('Instance', $id, 'instance');
    }
    
    /**
     * Switches the enabled flag of a Instance document.
     *
     * @Route("/{id}/switch", name="instance_switch")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function switchAction($id)
    {
        $document = $this->findQuery('Instance', $id);

        if (!$document)
        {
            throw $this->createNotFoundException('Unable to find Instance.');
        }
        
        $document->setEnabled(!$document->getEnabled());

        $dm = $this->getDocumentManager();
        $dm->persist($document);
        $dm->flush();

        return $this->redirect($this->generateUrl('instance'));
    }

}
