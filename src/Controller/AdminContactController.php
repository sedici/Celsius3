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

use Celsius3\Entity\Contact;
use Celsius3\Entity\Instance;
use Celsius3\Exception\Exception;
use Celsius3\Form\Type\AdminContactType;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Helper\CustomFieldHelper;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Repository\ContactRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Celsius3\Manager\InstanceManager;
use Celsius3\Form\Type\Filter\ContactFilterType;
use Celsius3\Form\Type\ContactType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Celsius3\Controller\BaseInstanceDependentController;


/**
 * AdminContact controller.
 *
 * @Route("/admin/contact")
 */
class AdminContactController extends BaseInstanceDependentController
{

    protected final function getEntity(): string
    { return Contact::class; }

    
    protected final function getType(): string
    { return AdminContactType::class; }


    protected function listQuery()
    {
        return $this->managerRegistry
            ->getRepository($this->entityClassName)
            ->createQueryBuilder('e')
            ->select('e')
            ->where('e.owningInstance = :instance')
            ->setParameter(
                'instance',
                $this->instanceHelper
                    ->getSessionOrUrlInstance()
                    ->getId()
            );
    }


    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.updatedAt',
            'defaultSortDirection' => 'desc',
        ];
    }


    /**
     * Lists all Contact entities.
     *
     * @Route("/", name="admin_contact")
     */
    public function index(PaginatorInterface $paginator): Response
    {
        $data = $this->baseIndex(
            null,
            $paginator
        );

        $deleteForms = [];
        foreach ($data['pagination'] as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm(
                $entity->getId()
            )->createView();
        }
        $data['deleteForms'] = $deleteForms;

        return $this->render(
            'Admin/Contact/index.html.twig',
            $data
        );
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
        $entity = $this->findQuery($id);

        if (!$entity) {
            throw Exception::create(
                Exception::ENTITY_NOT_FOUND,
                'exception.entity_not_found.contact'
            );
        }

        return $this->render(
            'Admin/Contact/show.html.twig',
            [
                'entity' => $entity,
            ]
        );
    }


    /**
     * Displays a form to create a new Contact entity.
     *
     * @Route("/new", name="admin_contact_new")
     */
    public function new(): Response
    {
        $contact = new Contact();
        $contact
            ->setOwningInstance($this->instance)
            ->setInstance($this->instance);

        return $this->render(
            'Admin/Contact/new.html.twig',
            $this->baseNew(
                $this->entityClassName,
                $contact,
                $this->typeClassName,
            )
        );
    }


    /**
     * Creates a new Contact entity.
     *
     * @Route("/create", name="admin_contact_create", methods={"POST"})
     */
    public function create()
    {
        $contact = new Contact();
        $contact
            ->setOwningInstance($this->getInstance())
            ->setInstance($this->getInstance());

        $response_params = $this->baseCreate(
            Contact::class,
            $contact,
            AdminContactType::class,
            [],
            'admin_contact'
        );
        
        if ($response_params instanceof RedirectResponse) {
            return $response_params;
        }

        return $this->render(
            'Admin/Contact/new.html.twig',
            $response_params
        );
    }





















    /**
     * Displays a form to edit an existing Contact entity.
     *
     * @Route("/{id}/edit", name="admin_contact_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id): Response
    {
        $contact = $this->findQuery($id);

        return $this->render(
            'Admin/Contact/edit.html.twig',
            $this->baseEdit(
                Contact::class,
                $id,
                AdminContactType::class,
                [
                    'user' => $contact->getUser(),
                ]
            )
        );
    }





    // /**
    //  * Displays a form to edit an existing Contact document.
    //  *
    //  * @Route("/{id}/edit", name="admin_contact_edit")
    //  *
    //  * @param string $id The document ID
    //  *
    //  * @throws NotFoundHttpException If document doesn't exists
    //  */
    // public function edit($id): Response
    // {
    //     $entity = $this->findQuery($id);
    //     if (!$entity) {
    //         throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.contact');
    //     }

    //     $editForm = $this->createForm(AdminContactType::class, $entity, [
    //         'owning_instance' => $this->getInstance(),
    //         'user' => $entity->getUser(),
    //     ]);

    //     return $this->render(
    //         'Admin/Contact/edit.html.twig',
    //         [
    //             'entity' => $entity,
    //             'edit_form' => $editForm->createView(),
    //         ]
    //     );
    // }

    // protected function findQuery($id)
    // {
    //     return $this->contactRepository
    //         ->findByInstance($this->getInstance(), $id);
    // }

    /**
     * Edits an existing Contact document.
     *
     * @Route("/{id}/update", name="admin_contact_update", methods={"POST"})
     *
     * @param string $id The document ID
     *
     * @throws NotFoundHttpException If document doesn't exists
     */
    public function update(Request $request, $id)
    {
        $entity = $this->findQuery($id);
        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.contact');
        }

        $editForm = $this->createForm(AdminContactType::class, $entity, [
            'owning_instance' => $this->getInstance(),
            'user' => $entity->getUser(),
        ]);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            try {
                $this->contactRepository->save($entity);

                $this->customFieldHelper->processCustomContactFields(
                    $this->getInstance(),
                    $editForm,
                    $entity
                );

                $this->addFlash(
                    'success',
                    $this->translator->trans(
                        'The %entity% was successfully edited.',
                        ['%entity%' => $this->translator->trans('Contact')],
                        'Flashes'
                    )
                );

                return $this->redirect($this->generateUrl('admin_contact_edit', ['id' => $id]));
            } catch (UniqueConstraintViolationException $exception) {
                $this->addFlash(
                    'error',
                    $this->translator->trans(
                        'The %entity% already exists.',
                        ['%entity%' => $this->translator->trans('Contact')],
                        'Flashes'
                    )
                );
            }
        }

        $this->addFlash(
            'error',
            $this->translator->trans(
                'There were errors editing the %entity%.',
                ['%entity%' => $this->translator->trans('Contact')],
                'Flashes'
            )
        );

        return $this->render('Admin/Contact/edit.html.twig', [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ]);
    }

    /**
     * Deletes a Contact document.
     *
     * @Route("/{id}/delete", name="admin_contact_delete", methods={"POST"})
     *
     * @param string $id The document ID
     *
     * @throws NotFoundHttpException If document doesn't exists
     */
    public function delete(Request $request, $id)
    {
        $form = $this->createFormBuilder(['id' => $id])
            ->add('id', HiddenType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity = $this->findQuery($id);

            if (!$entity) {
                throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.contact');
            }

            $this->contactRepository->remove($entity);

            $this->addFlash(
                'success',
                $this->translator->trans(
                    'The %entity% was successfully deleted.',
                    ['%entity%' => 'Contact'],
                    'Flashes'
                )
            );
        }

        return $this->redirect($this->generateUrl('admin_contact'));
    }

    // protected function listQuery()
    // {
    //     return $this->getDoctrine()->getManager()
    //         ->getRepository(Contact::class)
    //         ->createQueryBuilder('e')
    //         ->select('e')
    //         ->where('e.owningInstance = :instance')
    //         ->setParameter('instance', $this->instanceHelper->getSessionOrUrlInstance()->getId());
    // }

    // private function getInstance(): Instance
    // {
    //     return $this->instanceHelper->getSessionOrUrlInstance();
    // }
}
