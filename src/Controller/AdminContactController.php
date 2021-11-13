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

use Celsius3\CoreBundle\Entity\Contact;
use Celsius3\Exception\Exception;
use Celsius3\Form\Type\AdminContactType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Translator;

/**
 * AdminContact controller.
 *
 * @Route("/admin/contact")
 */
class AdminContactController extends BaseInstanceDependentController
{
    /**
     * Lists all Contact documents.
     *
     * @Route("/", name="admin_contact")
     */
    public function index(): Response
    {
        $data = $this->baseIndex('Contact');
        $deleteForms = [];

        foreach ($data['pagination'] as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }

        $data['deleteForms'] = $deleteForms;

        return $this->render('Admin/Contact/index.html.twig', $data);
    }

    /**
     * Finds and displays a Contact document.
     *
     * @Route("/{id}/show", name="admin_contact_show")
     *
     * @param string $id The document ID
     *
     * @throws NotFoundHttpException If document doesn't exists
     */
    public function show($id): Response
    {
        return $this->render('Admin/Contact/show.html.twig', $this->baseShow('Contact', $id));
    }

    /**
     * Displays a form to create a new Contact document.
     *
     * @Route("/new", name="admin_contact_new")
     */
    public function new(): Response
    {
        return $this->render(
            'Admin/Contact/new.html.twig',
            $this->baseNew('Contact', new Contact(), AdminContactType::class, [
                'owning_instance' => $this->getInstance(),
            ])
        );
    }

    /**
     * Creates a new Contact document.
     *
     * @Route("/create", name="admin_contact_create", methods={"POST"})
     */
    public function create()
    {
        $entity = new Contact();

        /** @var $translator Translator */
        $translator = $this->get('translator');

        $request = $this->get('request_stack')->getCurrentRequest();
        $form = $this->createForm(AdminContactType::class, $entity, [
            'owning_instance' => $this->getInstance(),
        ]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            try {
                $this->persistEntity($entity);
                $this->get('celsius3_core.custom_field_helper')->processCustomContactFields($this->getInstance(), $form, $entity);

                $this->addFlash('success', $translator->trans('The %entity% was successfully created.', ['%entity%' => $translator->trans('Contact')], 'Flashes'));

                return $this->redirect($this->generateUrl('admin_contact'));
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('error', $translator->trans('The %entity% already exists.', ['%entity%' => $translator->trans('Contact')], 'Flashes'));
            }
        }

        $this->addFlash('error', $translator->trans('There were errors creating the %entity%.', ['%entity%' => $translator->trans('Contact')], 'Flashes'));

        return $this->render('Admin/Contact/new.html.twig', [
            'entity' => $entity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Contact document.
     *
     * @Route("/{id}/edit", name="admin_contact_edit")
     *
     * @param string $id The document ID
     *
     * @throws NotFoundHttpException If document doesn't exists
     */
    public function edit($id): Response
    {
        $entity = $this->findQuery('Contact', $id);
        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.contact');
        }

        return $this->render('Admin/Contact/edit.html.twig',
            $this->baseEdit('Contact', $id, AdminContactType::class, [
            'owning_instance' => $this->getInstance(),
            'user' => $entity->getUser(),
        ]));
    }

    protected function findQuery($name, $id)
    {
        return $this->getDoctrine()->getManager()
            ->getRepository('Celsius3CoreBundle:' . $name)
            ->findByInstance($this->getInstance(), $id);
    }

    /**
     * Edits an existing Contact document.
     *
     * @Route("/{id}/update", name="admin_contact_update", methods={"POST"})
     *
     * @param string $id The document ID
     *
     * @throws NotFoundHttpException If document doesn't exists
     */
    public function update($id)
    {
        $entity = $this->findQuery('Contact', $id);
        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.contact');
        }

        /** @var $translator Translator */
        $translator = $this->get('translator');

        $entity = $this->findQuery('Contact', $id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.contact');
        }

        $edit_form = $this->createForm(AdminContactType::class, $entity, [
            'owning_instance' => $this->getInstance(),
            'user' => $entity->getUser(),
        ]);

        $request = $this->get('request_stack')->getCurrentRequest();

        $edit_form->handleRequest($request);

        if ($edit_form->isValid()) {
            try {
                $this->persistEntity($entity);
                $this->get('celsius3_core.custom_field_helper')->processCustomContactFields($this->getInstance(), $edit_form, $entity);

                $this->addFlash('success', $translator->trans('The %entity% was successfully edited.', ['%entity%' => $translator->trans('Contact')], 'Flashes'));

                return $this->redirect($this->generateUrl('admin_contact_edit', ['id' => $id]));
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('error', $translator->trans('The %entity% already exists.', ['%entity%' => $translator->trans('Contact')], 'Flashes'));
            }
        }

        $this->addFlash('error', $translator->trans('There were errors editing the %entity%.', ['%entity%' => $translator->trans('Contact')], 'Flashes'));

        return $this->render('Admin/Contact/edit.html.twig', [
            'entity' => $entity,
            'edit_form' => $edit_form->createView(),
        ]);
    }

    /**
     * Deletes a Contact document.
     *
     * @Route("/{id}/delete", name="admin_contact_delete", methods={"POST"})
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws NotFoundHttpException If document doesn't exists
     */
    public function delete($id)
    {
        return $this->baseDelete('Contact', $id, 'admin_contact');
    }

    protected function listQuery($name)
    {
        return $this->getDoctrine()->getManager()
            ->getRepository('Celsius3CoreBundle:' . $name)
            ->createQueryBuilder('e')
            ->select('e')
            ->where('e.owningInstance = :instance')
            ->setParameter('instance', $this->getInstance()->getId());
    }
}
