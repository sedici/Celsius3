<?php

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Instance controller.
 *
 * @Route("/admin/instance")
 */
class AdminInstanceController extends InstanceController
{

    /**
     * Displays a form to configure an existing Instance
     *
     * @Route("/configure", name="admin_instance_configure")
     * @Template()
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function configureAction()
    {
        return $this->baseConfigureAction($this->get('session')->get('instance_id'));
    }

    /**
     * Edits the existing Instance configuration.
     *
     * @Route("/{id}/update_configuration", name="admin_instance_update_configuration")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminInstance:configure.html.twig")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function configureUpdateAction($id)
    {
        return $this->baseConfigureUpdateAction($id, 'admin_instance');
    }

}
