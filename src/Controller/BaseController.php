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

use Celsius3\Entity\BaseUser;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Celsius3\Entity\Instance;
use Celsius3\Exception\Exception;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;


abstract class BaseController extends AbstractController
{
    /**
     * @var InstanceManager
     */
    protected $instanceManager;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var ConfigurationHelper
     */
    protected $configurationHelper;

    /**
     * @var PaginatorInterface
     */
    protected $paginator;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    protected string $entityClassName;
    protected string $typeClassName;
    protected string $templatePrefix;
    protected Instance $directory;
    protected ObjectManager $objectManager;

    public function __construct(
        InstanceManager $instanceManager,
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
        ConfigurationHelper $configurationHelper,
        TranslatorInterface $translator,
        ManagerRegistry $managerRegistry,
        ...$args
    ) {
        $this->instanceManager = $instanceManager;
        $this->entityManager = $entityManager;
        $this->configurationHelper = $configurationHelper;
        $this->paginator = $paginator;
        $this->translator = $translator;
        $this->managerRegistry = $managerRegistry;

        $this->entityClassName = $this->getEntity();
        $this->typeClassName = $this->getType();
        $this->templatePrefix = $this->getTemplatePrefix();
        $this->directory = $this->getDirectory();
        $this->objectManager = $this->objectManager;
    }


    // En realidad debe retornar una clase que herede de Entity pero no se como definirlo
    protected abstract function getEntity(): string;
    protected abstract function getType(): string;
    protected abstract function getTemplatePrefix(): string;
    protected abstract function getSortDefaults(): array;
    protected abstract function baseFilter($entity, $filter_form, $query);


    protected function filter($query) {}

    
    protected function listQuery()
    {
        return $this->managerRegistry
            ->getManager()
            ->getRepository($this->entityClassName)
            ->createQueryBuilder('e');
    }


    protected function findQuery(int $id)
    {
        return $this->managerRegistry
            ->getManager()
            ->getRepository($this->entityClassName)
            ->find($id);
    }


    protected function getDirectory()
    {
        return $this->instanceManager->getDirectory();
    }


    protected function getBundle()
    { return ''; }


    protected function getResultsPerPage()
    {
        return $this
            ->configurationHelper
            ->getCastedValue(
                $this->directory
                    ->get('results_per_page')
            );
    }


    protected function baseIndex(FormInterface $filter_form = null, $paginator)
    {
        $query = $this->listQuery();
        $request = $this->get('request_stack')->getCurrentRequest();
        if (!is_null($filter_form)) {
            $filter_form = $filter_form->handleRequest($request);
        }

        $pagination = $paginator->paginate($query, $request->query->get('page', 1)/* page number */ , $this->getResultsPerPage()/* limit per page */ , $this->getSortDefaults());

        return [
            'pagination' => $pagination,
            'filter_form' => (!is_null($filter_form)) ? $filter_form->createView() : $filter_form,
        ];
    }


    protected function baseShow($name, $id)
    {
        $entity = $this->findQuery($name, $id);

        if (!$entity) {
            throw Exception::create(
                Exception::ENTITY_NOT_FOUND,
                'exception.entity_not_found.' . $name
            );
        }

        return [
            'entity' => $entity,
        ];
    }


    protected function baseNew($name, $entity, $type, array $options = [])
    {
        $form = $this->createForm($type, $entity, $options);

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }


    protected function persistEntity($entity)
    {
        $this->objectManager->persist($entity);
        $this->objectManager->flush();
    }


    protected function baseCreate($name, $entity, $type, array $options, $route)
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $form = $this->createForm($type, $entity, $options);
        $form->handleRequest($request);
        if ($form->isValid()) {
            try {
                $this->persistEntity($entity);
                $this->addFlash(
                    'success',
                    $this->translator->trans(
                        'The %entity% was successfully created.',
                        [
                            '%entity%' => $this->translator->trans($name)
                        ],
                        'Flashes'
                    )
                );

                return $this->redirect($this->generateUrl($route));
            } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                $this->addFlash(
                    'error',
                    $this->translator->trans(
                        'The %entity% already exists.',
                        [
                            '%entity%' => $this->translator->trans($name)
                        ],
                        'Flashes'
                    )
                );
            }
        }

        $this->addFlash(
            'error',
            $this->translator->trans(
                'There were errors creating the %entity%.',
                [
                    '%entity%' => $this->translator->trans($name)
                ],
                'Flashes'
            )
        );

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }


    protected function baseEdit($name, $id, $type, array $options = [], $route = null)
    {
        $entity = $this->findQuery($id);

        if (!$entity) {
            throw Exception::create(
                Exception::ENTITY_NOT_FOUND,
                'exception.entity_not_found.' . $name
            );
        }

        $editForm = $this->createForm(
            $type,
            $entity,
            $options
        );

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'route' => $route,
        ];
    }


    protected function baseUpdate($name, $id, $type, array $options, $route)
    {
        $entity = $this->findQuery($id);

        if (!$entity) {
            throw Exception::create(
                Exception::ENTITY_NOT_FOUND,
                'exception.entity_not_found.' . $name
            );
        }

        $editForm = $this->createForm($type, $entity, $options);

        $request = $this->get('request_stack')->getCurrentRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            try {
                $this->persistEntity($entity);

                $this->addFlash('success', $this->translator->trans(
                    'The %entity% was successfully edited.',
                    [
                        '%entity%' => $this->translator->trans($name)
                    ],
                    'Flashes'
                ));

                return $this->redirect($this->generateUrl((string) $route . '_edit', ['id' => $id]));
            } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                $this->addFlash('error', $this->translator->trans(
                    'The %entity% already exists.',
                    [
                        '%entity%' => $this->translator->trans($name)
                    ],
                    'Flashes'
                ));
            }
        }

        $this->addFlash('error', $this->translator->trans(
            'There were errors editing the %entity%.',
            [
                '%entity%' => $this->translator->trans($name)
            ],
            'Flashes'
        ));

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView()
        ];
    }


    protected function baseDelete($name, $id, $route)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->get('request_stack')->getCurrentRequest();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity = $this->findQuery($name, $id);

            if (!$entity) {
                throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found.' . $name);
            }

            $em = $this->objectManager;
            $em->remove($entity);
            $em->flush();

            /** @var $translator Translator */
            $translator = $this->get('translator');

            $this->addFlash('success', $this->translator->trans('The %entity% was successfully deleted.', ['%entity%' => $name], 'Flashes'));
        }

        return $this->redirect($this->generateUrl($route));
    }


    protected function baseBatch()
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $action = $request->request->get('action');
        $function = 'batch' . ucfirst($action);
        $element_ids = $request->request->get('element', array());

        return $this->$function($element_ids);
    }

    protected function baseUnion($name, $ids)
    {
        $em = $this->objectManager;
        $entities = $em
            ->getRepository('Celsius3:' . $name)
            ->findBy(['id' => $ids]);

        return [
            'entities' => $entities,
        ];
    }


    protected function baseDoUnion($name, $ids, $main_id, $route, $updateInstance = true)
    {
        $em = $this->objectManager;

        $main = $em->getRepository($name)->find($main_id);

        if (!$main) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found');
        }

        $entities = $em->getRepository($name)->findBaseDoUnionEntities($main, $ids);

        if (count($entities) !== count($ids) - 1) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found');
        }

        if ($name === BaseUser::class) {
            $this->mergeSecondaryInstances($main, $entities);
        }

        $this->get('celsius3_core.union_manager')
            ->union($name, $main, $entities, $updateInstance);

        /** @var $translator Translator */
        $translator = $this->get('translator');

        $this->addFlash('success', $this->translator->trans('The %entities% were successfully joined.', ['%entities%' => $this->translator->transChoice($name, count($entities), [], 'Flashes')]));

        return $this->redirect($this->generateUrl($route));
    }


    protected function createDeleteForm($id)
    {
        return $this->createFormBuilder(array(
            'id' => $id,
        ))
            ->add('id', HiddenType::class)
            ->getForm();
    }


    protected function ajax(Request $request, Instance $instance = null, $librarian = null)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        $target = $request->query->get('target');
        if (!$this->validateAjax($target)) {
            throw $this->createNotFoundException();
        }

        $term = $request->query->get('term');

        if ($this->isGranted('ROLE_ADMIN')) {
            $insts = array();
        } else {
            $insts = $this->get('celsius3_core.user_manager')->getLibrarianInstitutions($librarian);
        }

        $result = $this->objectManager
            ->getRepository('Celsius3' . $target)
            ->findByTerm($term, $instance, null, $insts)
            ->getResult();

        $json = [];


        foreach ($result as $element) {
            if (method_exists($element, 'asJson')) {
                $json[] = $element->asJSon();
            } else {
                $json[] = array(
                    'id' => $element->getId(),
                    'value' => ($target === 'BaseUser') ? $element->__toString() . ' (' . $element->getUsername() . ')' : $element->__toString(),

                );
            }
        }


        $response = new Response(json_encode($json));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    protected function validateAjax($target)
    {
        return false;
    }
}
