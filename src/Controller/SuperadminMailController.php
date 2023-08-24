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

namespace Celsius3\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\Entity\MailTemplate;
use Celsius3\Form\Type\MailTemplateType;
use Celsius3\Form\Type\Filter\MailTemplateFilterType;
use Celsius3\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;

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
            ->getRepository(MailTemplate::class)
            ->createQueryBuilder('e')
            ->where('e.instance = :instance')
            ->setParameter('instance', $this->getDirectory()->getId());
    }

    /**
     * Lists all Templates Mail.
     *
     * @Route("/", name="superadmin_mails")
     */
    public function index(PaginatorInterface $paginator): Response
    {
        return $this->render(
            'Superadmin/Mail/index.html.twig',
            $this->baseIndex('MailTemplate', $this->createForm(MailTemplateFilterType::class), $paginator)
        );
    }

    /**
     * Displays a form to create a new mail template.
     *
     * @Route("/new", name="superadmin_mails_new")
     */
    public function new(): Response
    {
        return $this->render(
            'Superadmin/Mail/new.html.twig',
            $this->baseNew('MailTemplate', new MailTemplate(), MailTemplateType::class, [
                'instance' => $this->getDirectory(),
            ])
        );
    }

    /**
     * Displays a form to edit an existing mail template.
     *
     * @Route("/{id}/edit", name="superadmin_mails_edit")
     *
     * @param string $id The mail template ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id)
    {
        return $this->render(
            'Superadmin/Mail/edit.html.twig',
            $this->baseEdit('MailTemplate', $id, MailTemplateType::class, [
                'instance' => $this->getDirectory(),
            ])
        );
    }

    /**
     * Creates a new Mail Entity.
     *
     * @Route("/create", name="superadmin_mails_create", methods={"POST"})
     */
    public function create()
    {
        return $this->render('Superadmin/Mail/new.html.twig', $this->baseCreate('MailTemplate', new MailTemplate(), MailTemplateType::class, array(
                    'instance' => $this->getDirectory(),
                        ), 'superadmin_mails'));
    }

    /**
     * Edits an existing Mail TEmplate.
     *
     * @Route("/{id}/update", name="superadmin_mails_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update($id)
    {
        return $this->render('Superadmin/Mail/edit.html.twig', $this->baseUpdate('MailTemplate', $id, MailTemplateType::class, array(
                    'instance' => $this->getDirectory(),
                        ), 'superadmin_mails'));
    }

    /**
     * Change state an existing Mail TEmplate.
     *
     * @Route("/{id}/change_state", name="superadmin_mails_change_state")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function changeState($id): Response
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
            ->add(
                'success',
                'The Template was successfully '
                . (($template->getEnabled()) ? 'enabled' : 'disabled')
            );

        return $this->redirect($this->generateUrl('superadmin_mails'));
    }
}
