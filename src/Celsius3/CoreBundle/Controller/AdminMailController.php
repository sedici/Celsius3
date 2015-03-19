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
use Celsius3\CoreBundle\Entity\MailTemplate;
use Celsius3\CoreBundle\Form\Type\MailTemplateType;
use Celsius3\CoreBundle\Filter\Type\MailTemplateFilterType;

/**
 * Order controller.
 *
 * @Route("/admin/mail")
 */
class AdminMailController extends BaseInstanceDependentController
{

    protected function listQuery($name)
    {
        return $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3CoreBundle:' . $name)
                        ->findGlobalAndForInstance($this->getInstance(), $this->getDirectory());
    }

    protected function findQuery($name, $id)
    {
        return $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3CoreBundle:' . $name)->find($id);
    }

    /**
     * Lists all Templates Mail.
     *
     * @Route("/", name="admin_mails")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('MailTemplate', $this->createForm(new MailTemplateFilterType()));
    }

    /**
     * Displays a form to create a new mail template.
     *
     * @Route("/new", name="admin_mails_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('MailTemplate', new MailTemplate(), new MailTemplateType($this->getInstance()));
    }

    /**
     * Displays a form to edit an existing mail template.
     *
     * @Route("/{id}/edit", name="admin_mails_edit")
     * @Template()
     *
     * @param string $id The mail template ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function editAction($id)
    {
        //Se debe determinar si se utilizara admin_mails_edit o admin_mails_create, dependiendo
        //si la plantilla le pertenece al directorio o a la instancia.
        $template = $this->findQuery('MailTemplate', $id);

        if ($template->getInstance()) {
            $route = 'update';
        } else {
            $route = 'create';
        }

        return $this->baseEdit('MailTemplate', $id, new MailTemplateType($this->getInstance()), $route);
    }

    /**
     * Creates a new Mail Entity.
     *
     * @Route("/create", name="admin_mails_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminMail:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('MailTemplate', new MailTemplate(), new MailTemplateType($this->getInstance()), 'admin_mails');
    }

    /**
     * Edits an existing Mail TEmplate.
     *
     * @Route("/{id}/update", name="admin_mails_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminMail:edit.html.twig")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('MailTemplate', $id, new MailTemplateType($this->getInstance()), 'admin_mails');
    }

    /**
     * Change state an existing Mail TEmplate.
     *
     * @Route("/{id}/change_state", name="admin_mails_change_state")
     *
     * @Template()
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function changeStateAction($id)
    {
        $template = $this->findQuery('MailTemplate', $id);

        if (!$template || $template->getInstance()->getId() === $this->getDirectory()->getId()) {
            throw $this->createNotFoundException('Unable to find template.');
        }

        $template->setEnabled(!$template->getEnabled());

        $em = $this->getDoctrine()->getManager();
        $em->persist($template);
        $em->flush();

        $this->get('session')->getFlashBag()
                ->add('success', 'The Template was successfully ' . (($template->getEnabled()) ? 'enabled' : 'disabled'));

        return $this->redirect($this->generateUrl('admin_mails'));
    }
}
