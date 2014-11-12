<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Entity\Instance;
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
     * Lists all Instance entities.
     *
     * @Route("/", name="superadmin_instance")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('Instance', $this->createForm(new InstanceFilterType()));
    }

    /**
     * Displays a form to create a new Instance entity.
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
     * Creates a new Instance entity.
     *
     * @Route("/create", name="superadmin_instance_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminInstance:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Instance', new Instance(), new InstanceType(), 'superadmin_instance');
    }

    /**
     * Displays a form to edit an existing Instance entity.
     *
     * @Route("/{id}/edit", name="superadmin_instance_edit")
     * @Template()
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('Instance', $id, new InstanceType());
    }

    /**
     * Edits an existing Instance entity.
     *
     * @Route("/{id}/update", name="superadmin_instance_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminInstance:edit.html.twig")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('Instance', $id, new InstanceType(), 'superadmin_instance');
    }

    /**
     * Switches the enabled flag of a Instance entity.
     *
     * @Route("/{id}/switch", name="superadmin_instance_switch")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function switchAction($id)
    {
        $entity = $this->findQuery('LegacyInstance', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Instance.');
        }

        $entity->setEnabled(!$entity->getEnabled());

        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'The Instance was successfully ' . (($entity->getEnabled()) ? 'enabled' : 'disabled'));

        return $this->redirect($this->generateUrl($entity->isCurrent() ? 'superadmin_instance' : 'superadmin_instance_legacy'));
    }

    /**
     * Displays a form to configure the Directory
     *
     * @Route("/directory/configure", name="superadmin_directory_configure")
     * @Template("Celsius3CoreBundle:SuperadminInstance:configure.html.twig")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function configureDirectoryAction()
    {
        return $this->baseConfigureAction($this->get('celsius3_core.instance_manager')->getDirectory()->getId());
    }

    /**
     * Displays a form to configure an existing Instance
     *
     * @Route("/{id}/configure", name="superadmin_instance_configure")
     * @Template()
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
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
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function configureUpdateAction($id)
    {
        return $this->baseConfigureUpdateAction($id, 'superadmin_instance');
    }

    /**
     * Redirects to the administration of an Instance entity.
     *
     * @Route("/{id}/admin", name="superadmin_instance_admin")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function adminAction($id)
    {
        $entity = $this->findQuery('Instance', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Instance.');
        }

        $this->get('session')->set('instance_id', $entity->getId());
        $this->get('session')->set('instance_url', $entity->getUrl());

        return $this->redirect($this->generateUrl('administration'));
    }
}
