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

use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\InstanceManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Entity\MailTemplate;
use Celsius3\Form\Type\MailTemplateType;
use Celsius3\Form\Type\Filter\MailTemplateFilterType;
use Celsius3\Exception\Exception;
use Celsius3\Validator\Constraints as CelsiusAssert;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Translator;

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
                        ->getRepository('Celsius3CoreBundle:'.$name)
                        ->findGlobalAndForInstance($this->getInstance(), $this->getDirectory());
    }

    protected function findQuery($name, $id)
    {
        return $this->getDoctrine()->getManager()
                        ->getRepository('Celsius3CoreBundle:'.$name)->find($id);
    }

    /**
     * Lists all Templates Mail.
     *
     * @Route("/", name="admin_mails")
     */
    public function index(): Response
    {
        return $this->render(
            'Admin/Mail/index.html.twig',
            $this->baseIndex(
                'MailTemplate',
                $this->createForm(MailTemplateFilterType::class)
            )
        );
    }

    /**
     * Displays a form to create a new mail template.
     *
     * @Route("/new", name="admin_mails_new")
     */
    public function new(): Response
    {
        return $this->render(
            'Admin/Mail/new.html.twig',
            $this->baseNew(
                'MailTemplate',
                new MailTemplate(),
                MailTemplateType::class,
                [
                    'instance' => $this->getInstance(),
                    'super_admin' => $this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')
                ]
            )
        );
    }

    /**
     * Displays a form to edit an existing mail template.
     *
     * @Route("/{id}/edit", name="admin_mails_edit")
     *
     * @param string $id The mail template ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id)
    {
        //Se debe determinar si se utilizara admin_mails_edit o admin_mails_create, dependiendo
        //si la plantilla le pertenece al directorio o a la instancia.
        $template = $this->findQuery('MailTemplate', $id);

        if ($template->getInstance() !== $this->getDirectory()) {
            $route = $this->generateUrl('admin_mails_update', ['id' => $id]);
        } else {
            $result = $this->getDoctrine()->getManager()
                ->getRepository(MailTemplate::class)
                ->findBy(['code' => $template->getCode(), 'instance' => $this->getInstance()]);

            if (count($result) > 0) {
                return $this->redirectToRoute('admin_mails');
            }

            $route = $this->generateUrl('admin_mails_create');
        }

        return $this->render(
            'Admin/Mail/edit.html.twig',
            $this->baseEdit('MailTemplate', $id, MailTemplateType::class, [
                'instance' => $this->getInstance(),
                'code' => $template->getCode(),
                'action' => $route,
                'super_admin' => $this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')
            ])
        );
    }

    /**
     * Creates a new Mail Entity.
     *
     * @Route("/create", name="admin_mails_create", methods={"POST"})
     *
     */
    public function create()
    {
        /** @var $translator Translator */
        $translator = $this->get('translator');

        $entity = new MailTemplate();
        $request = $this->get('request_stack')->getCurrentRequest();
        $form = $this->createForm(MailTemplateType::class, $entity, ['instance' => $this->getInstance()]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $errorList = $this->get('validator')->validate($entity->getText(), new CelsiusAssert\MailTemplate());
            if (0 === count($errorList)) {
                try {
                    $this->persistEntity($entity);
                    $this->addFlash('success', $translator->trans('The %entity% was successfully created.', ['%entity%' => $translator->trans('MailTemplate')], 'Flashes'));

                    return $this->redirect($this->generateUrl('admin_mails'));
                } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                    $this->addFlash('error', $translator->trans('The %entity% already exists.', ['%entity%' => $translator->trans('MailTemplate')], 'Flashes'));
                }
            }
        }

        $this->addFlash('error', $translator->trans('There were errors creating the %entity%.', ['%entity%' => $translator->trans('MailTemplate')], 'Flashes'));

        return $this->render('Admin/Mail/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Edits an existing Mail TEmplate.
     *
     * @Route("/{id}/update", name="admin_mails_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update($id)
    {
        /** @var $translator Translator */
        $translator = $this->get('translator');

        $entity = $this->findQuery('MailTemplate', $id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.mail_template');
        }

        $editForm = $this->createForm(MailTemplateType::class, $entity, ['instance' => $this->getInstance()]);

        $request = $this->get('request_stack')->getCurrentRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $errorList = $this->get('validator')->validate($entity->getText(), new CelsiusAssert\MailTemplate());
            if (0 === count($errorList)) {
                try {
                    $this->persistEntity($entity);

                    $this->addFlash('success', $translator->trans('The %entity% was successfully edited.', ['%entity%' => $translator->trans('MailTemplate')], 'Flashes'));

                    return $this->redirect($this->generateUrl('admin_mails_edit', array('id' => $id)));
                } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                    $this->addFlash('error', $translator->trans('The %entity% already exists.', ['%entity%' => $translator->trans('MailTemplate')], 'Flashes'));
                }
            } else {
                $editForm->get('text')->addError(new FormError('error.invalid.mail_template'));
            }
        }

        $this->addFlash('error', $translator->trans('There were errors editing the %entity%.', ['%entity%' => $translator->trans('MailTemplate')], 'Flashes'));

        return $this->render('Admin/Mail/edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Change state an existing Mail TEmplate.
     *
     * @Route("/{id}/change_state", name="admin_mails_change_state")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function changeState($id): Response
    {
        $template = $this->findQuery('MailTemplate', $id);

        if (!$template || $template->getInstance()->getId() === $this->getDirectory()->getId()) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.mail_template');
        }

        $template->setEnabled(!$template->getEnabled());

        $em = $this->getDoctrine()->getManager();
        $em->persist($template);
        $em->flush();

        $this->get('session')->getFlashBag()
                ->add('success', 'The Template was successfully '.(($template->getEnabled()) ? 'enabled' : 'disabled'));

        return $this->redirect($this->generateUrl('admin_mails'));
    }
}