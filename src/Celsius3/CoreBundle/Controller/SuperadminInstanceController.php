<?php

namespace Celsius3\CoreBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Form\Type\InstanceType;
use Celsius3\CoreBundle\Filter\Type\InstanceFilterType;

/**
 * Instance controller.
 *
 * @Route("/superadmin/instance")
 */
class SuperadminInstanceController extends InstanceController
{

    /**
     * Lists all Instance documents.
     *
     * @Route("/", name="superadmin_instance")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this
                ->baseIndex('Instance',
                        $this->createForm(new InstanceFilterType()));
    }

    /**
     * Displays a form to create a new Instance document.
     *
     * @Route("/new", name="superadmin_instance_new")
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
     * @Route("/create", name="superadmin_instance_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminInstance:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this
                ->baseCreate('Instance', new Instance(), new InstanceType(),
                        'superadmin_instance');
    }

    /**
     * Displays a form to edit an existing Instance document.
     *
     * @Route("/{id}/edit", name="superadmin_instance_edit")
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
     * @Route("/{id}/update", name="superadmin_instance_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminInstance:edit.html.twig")
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
                ->baseUpdate('Instance', $id, new InstanceType(),
                        'superadmin_instance');
    }

    /**
     * Deletes a Instance document.
     *
     * @Route("/{id}/delete", name="superadmin_instance_delete")
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
        return $this->baseDelete('Instance', $id, 'superadmin_instance');
    }

    /**
     * Switches the enabled flag of a Instance document.
     *
     * @Route("/{id}/switch", name="superadmin_instance_switch")
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

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Instance.');
        }

        $document->setEnabled(!$document->getEnabled());

        $dm = $this->getDocumentManager();
        $dm->persist($document);
        $dm->flush();

        $this->get('session')->getFlashBag()
                ->add('success',
                        'The Instance was successfully '
                                . (($document->getEnabled()) ? 'enabled'
                                        : 'disabled'));

        return $this->redirect($this->generateUrl('superadmin_instance'));
    }

    /**
     * Displays a form to configure an existing Instance
     *
     * @Route("/{id}/configure", name="superadmin_instance_configure")
     * @Template()
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function configureAction($id)
    {
        return $this->baseConfigureAction($id);
    }

    /**
     * Edits the existing Instance configuration.
     *
     * @Route("/{id}/update_configuration", name="superadmin_instance_update_configuration")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminInstance:configure.html.twig")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function configureUpdateAction($id)
    {
        return $this->baseConfigureUpdateAction($id, 'superadmin_instance');
    }

    /**
     * Redirects to the administration of an Instance document.
     *
     * @Route("/{id}/admin", name="superadmin_instance_admin")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function adminAction($id)
    {
        $document = $this->findQuery('Instance', $id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Instance.');
        }

        $this->get('session')->set('instance_id', $document->getId());
        $this->get('session')->set('instance_url', $document->getUrl());

        return $this->redirect($this->generateUrl('administration'));
    }
}
