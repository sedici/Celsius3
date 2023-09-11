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

use Celsius3\Entity\Instance;
use Celsius3\Entity\Institution;
use Celsius3\Exception\Exception;
use Celsius3\Form\Type\Filter\InstanceFilterType;
use Celsius3\Form\Type\InstanceType;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Helper\InstanceHelper;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Translator;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Instance controller.
 *
 * @Route("/superadmin/instance")
 */
class SuperadminInstanceController extends InstanceController
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ConfigurationHelper
     */
    private $configurationHelper;
    /**
     * @var InstanceHelper
     */
    private $instanceHelper;


    public function __construct(
        PaginatorInterface $paginator,
        EntityManagerInterface $entityManager,
        ConfigurationHelper $configurationHelper,
        InstanceHelper $instanceHelper

    ) {
        $this->instanceHelper= $instanceHelper;
        $this->paginator = $paginator;
        $this->entityManager = $entityManager;
        $this->setConfigurationHelper($configurationHelper);

    }

    protected function getSortDefaults()
    {
        return array(
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        );
    }

    /**
     * Lists all Instance entities.
     *
     * @Route("/", name="superadmin_instance")
     */
    public function index(): Response
    {
        return $this->render(
            'Superadmin/Instance/index.html.twig',
            $this->baseIndex('Instance', $this->createForm(InstanceFilterType::class), $this->paginator)
        );
    }

    /**
     * Displays a form to create a new Instance entity.
     *
     * @Route("/new", name="superadmin_instance_new")
     */
    public function new(): Response
    {
        $entity = new Instance();
        $form = $this->createForm(InstanceType::class, $entity, ['institution_select' => true]);

        return $this->render('Superadmin/Instance/new.html.twig', [
            'entity' => $entity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a new Instance entity.
     *
     * @Route("/create", name="superadmin_instance_create", methods={"POST"})
     */
    public function create()
    {
        /** @var Translator $translator */
        $translator = $this->get('translator');
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request_stack')->getCurrentRequest();

        $instance = new Instance();
        $form = $this->createForm(InstanceType::class, $instance, ['institution_select' => true]);

        $form->handleRequest($request);
        if ($form->isValid()) {
            try {
                $institution = $em->getRepository(Institution::class)
                    ->find($request->request->get('instance')['institution']);
                if (is_null($institution)) {
                    throw Exception::create(Exception::ENTITY_NOT_FOUND, 'Not found institution');
                }
                $em->transactional(function (EntityManager $em) use ($instance, $institution) {
                    $em->persist($instance);
                    $em->flush($instance);

                    $institution->setCelsiusInstance($instance);
                    $em->persist($institution);
                    $em->flush($institution);
                });

                $this->get('celsius3_core.file_manager')->createFilesDirectory($instance->getUrl());

                $this->addFlash('success', $translator->trans('The %entity% was successfully created.', ['%entity%' => $translator->trans('Instance')], 'Flashes'));

                return $this->redirect($this->generateUrl('superadmin_instance'));
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('error', $translator->trans('The %entity% already exists.', ['%entity%' => $translator->trans('Instance')], 'Flashes'));
            } catch (\Exception $e) {
                $this->addFlash('error', $translator->trans('Error to persist %entity%.', ['%entity%' => $translator->trans('Instance')], 'Flashes'));
            }
        }

        $this->addFlash('error', $translator->trans('There were errors creating the %entity%.', ['%entity%' => $translator->trans('Instance')], 'Flashes'));

        return $this->render('Superadmin/Instance/new.html.twig', array(
            'entity' => $instance,
            'form' => $form->createView(),
        ));
    }

    private function getErrorMessages(Form $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }

    /**
     * Displays a form to edit an existing Instance entity.
     *
     * @Route("/{id}/edit", name="superadmin_instance_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit($id): Response
    {
        return $this->render(
            'Superadmin/Instance/edit.html.twig',
            $this->baseEdit('Instance', $id, InstanceType::class)
        );
    }

    /**
     * Edits an existing Instance entity.
     *
     * @Route("/{id}/update", name="superadmin_instance_update", methods={"POST"})
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update($id)
    {
        return $this->render('Superadmin/Instance/edit.html.twig', $this->baseUpdate('Instance', $id, InstanceType::class, array(), 'superadmin_instance'));
    }

    /**
     * Switches the enabled flag of a Instance entity.
     *
     * @Route("/{id}/switch", name="superadmin_instance_switch")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function switch($id)
    {
        $entity = $this->findQuery('LegacyInstance', $id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.instance');
        }

        $entity->setEnabled(!$entity->getEnabled());

        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'The Instance was successfully '.(($entity->getEnabled()) ? 'enabled' : 'disabled'));

        return $this->redirect($this->generateUrl($entity->isCurrent() ? 'superadmin_instance' : 'superadmin_instance_legacy'));
    }

    /**
     * Switches the enabled flag of a Instance entity.
     *
     * @Route("/{id}/invisible", name="superadmin_instance_invisible")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function invisible($id)
    {
        $entity = $this->findQuery('Instance', $id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.instance');
        }

        $entity->setInvisible(!$entity->getInvisible());

        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'The Instance was successfully '.(($entity->getInvisible()) ? 'hidden' : 'show'));

        return $this->redirect($this->generateUrl($entity->isCurrent() ? 'superadmin_instance' : 'superadmin_instance_legacy'));
    }

    /**
     * Displays a form to configure the Directory.
     *
     * @Route("/directory/configure", name="superadmin_directory_configure")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function configureDirectory()
    {
        return $this->render('Superadmin/Instance/configure.html.twig', $this->baseConfigure($this->get('celsius3_core.instance_manager')->getDirectory()->getId()));
    }

    /**
     * Displays a form to configure an existing Instance.
     *
     * @Route("/{id}/configure", name="superadmin_instance_configure")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function configure($id)
    {
        return $this->render(
            'Superadmin/Instance/configure.html.twig',
            $this->baseConfigure($id)
        );
    }

    /**
     * Edits the existing Instance configuration.
     *
     * @Route("/{id}/update_configuration", name="superadmin_instance_update_configuration", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function configureUpdate($id)
    {
        return $this->render('Superadmin/Instance/configure.html.twig', $this->baseConfigureUpdate($id, 'superadmin_instance'));
    }

    /**
     * Redirects to the administration of an Instance entity.
     *
     * @Route("/{id}/admin", name="superadmin_instance_admin")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function admin($id)
    {
        $entity = $this->findQuery('Instance', $id);

        if (!$entity) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.instance');
        }

        $this->get('session')->set('instance_id', $entity->getId());
        $this->get('session')->set('instance_url', $entity->getUrl());
        $this->get('session')->set('instance_host', $entity->getHost());

        $this->get('session')->set('admin_instance', $this->instanceHelper->getSessionOrUrlInstance());

        return $this->redirect($this->generateUrl('administration'));
    }
}
