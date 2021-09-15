<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
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
use Celsius3\Form\Type\MailTemplateType;
use Celsius3\Form\Type\Filter\MailTemplateFilterType;
use Celsius3\CoreBundle\Exception\Exception;

/**
 * Order controller.
 *
 * @Route("/superadmin/mail")
 */
class SuperadminMailController extends BaseController
{
    protected function listQuery($name)
    {
        return $this->getDoctrine()->getManager()
            ->getRepository('Celsius3CoreBundle:'.$name)
            ->createQueryBuilder('e')
            ->where('e.instance = :instance')
            ->setParameter('instance', $this->getDirectory()->getId());
    }

    /**
     * Lists all Templates Mail.
     *
     * @Route("/", name="superadmin_mails")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('MailTemplate', $this->createForm(MailTemplateFilterType::class));
    }

    /**
     * Displays a form to create a new mail template.
     *
     * @Route("/new", name="superadmin_mails_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('MailTemplate', new MailTemplate(), MailTemplateType::class, array(
                    'instance' => $this->getDirectory(),
        ));
    }

    /**
     * Displays a form to edit an existing mail template.
     *
     * @Route("/{id}/edit", name="superadmin_mails_edit")
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
        return $this->baseEdit('MailTemplate', $id, MailTemplateType::class, array(
                    'instance' => $this->getDirectory(),
        ));
    }

    /**
     * Creates a new Mail Entity.
     *
     * @Route("/create", name="superadmin_mails_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminMail:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('MailTemplate', new MailTemplate(), MailTemplateType::class, array(
                    'instance' => $this->getDirectory(),
                        ), 'superadmin_mails');
    }

    /**
     * Edits an existing Mail TEmplate.
     *
     * @Route("/{id}/update", name="superadmin_mails_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:SuperadminMail:edit.html.twig")
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('MailTemplate', $id, MailTemplateType::class, array(
                    'instance' => $this->getDirectory(),
                        ), 'superadmin_mails');
    }

    /**
     * Change state an existing Mail TEmplate.
     *
     * @Route("/{id}/change_state", name="superadmin_mails_change_state")
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

        if (!$template) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.mail_template');
        }

        $template->setEnabled(!$template->getEnabled());

        $em = $this->getDoctrine()->getManager();
        $em->persist($template);
        $em->flush();

        $this->get('session')->getFlashBag()
                ->add('success', 'The Template was successfully '
                        .(($template->getEnabled()) ? 'enabled' : 'disabled'));

        return $this->redirect($this->generateUrl('superadmin_mails'));
    }
}
