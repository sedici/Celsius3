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

namespace Celsius3\Controller\Base;

use Celsius3\Entity\BaseUser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Celsius3\Entity\Instance;
use Celsius3\Exception\Exception;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\QueryBuilder;
use Exception as GlobalException;
use ReflectionClass;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\Pagination\PaginationInterface;

abstract class BaseEntityController extends BaseController
{

    protected string $entityClassName;
    protected string $typeClassName;
    protected array $sortDefaults;
    protected ReflectionClass $entityClass;
    protected string $filterClassName;
    protected string $entityClassShortName;


    public function initialize(): void
    {
        $this->entityClassName = $this->getEntity();
        $this->entityClass = $this->getEntityClass();
        $this->entityClassShortName = $this->entityClass->getShortName();
        $this->typeClassName = $this->getType();
        $this->repository = $this->getRepository();
        $this->sortDefaults = $this->getSortDefaults();
        $this->filterClassName = $this->getFilterType();

        parent::initialize();
    }


    // En realidad debe retornar una clase que herede de Entity pero no se como definirlo
    abstract protected function getEntity(): string;
    // abstract protected function getType(): string;
    abstract protected function getSortDefaults(): array;

    
    final protected function getEntityClass(): ReflectionClass
    { return new ReflectionClass($this->entityClassName); }
    protected function getType(): string
    { return (string) 'Celsius3\Form\Type\\' . $this->entityClassShortName . 'Type'; }
    protected function getFilterType(): string
    { return (string) 'Celsius3\Form\Type\Filter\\' . $this->entityClassShortName . 'FilterType'; }
    
    
    protected function getDirectory(): Instance|null
    { return $this->instanceManager->getDirectory(); }


    protected function getRepository(): EntityRepository
    {
        return $this->entityManager
            ->getRepository($this->entityClassName);
    }


    protected function error(
        string $type,
        string $entity = null,
        string $msg = null
    ): never {
        if ($msg === null) {
            if ($entity === null)
                $entity = $this->entityClass->getShortName();
            $msg = (string) 'exception.' . $type . $entity;
        }

        throw Exception::create($type, $msg);
    }


    protected function addEntityFlash(
        string $type, string $id, array $entities = null
    ): void {
        $this->addFlash(
            $type,
            $this->translator->trans(
                $id,
                ($entities === null)
                    ? [ '%entity%' => $this->translator->trans(
                        $this->entityClass->getShortName()
                    ) ]
                    : [ '%entities%' => $this->translator->trans(
                        $this->entityClass->getShortName(),
                        [ '%count%' => count($entities) ],
                        'Flashes'
                    ) ],
                'Flashes'
            )
        );
    }


    public function baseIndex1(
        string $type = null,
        array $formOptions = [],
        $data = null,
        FormInterface $filter_form = null,
        bool $hasFilterForm = true
    ): array {
        if ($type === null) $type = $this->filterClassName;

        // ---

        $request = $this->requestStack->getCurrentRequest();
        
        $query = $this->listQuery();

        if ($hasFilterForm) {
            $filter_form = $this->createForm(
                $type, $data, $formOptions
            );
 
            $filter_form->handleRequest($request);
    
            $query = $this->filterManager->filter(
                $query, $filter_form, $this->entityClassName, $this->instance
            );
        }

        return [
            'pagination' => $this->paginate($query),
            'filter_form' => ($filter_form !== null)
                ? $filter_form->createView()
                : $filter_form,
        ];
    }


    protected function baseIndex(
        string $type = null,
        array $formOptions = [],
        string $template = null,
        $data = null,
        FormInterface $filter_form = null,
        bool $hasFilterForm = true
    ): Response {
        if ($template === null) 
            $template = (string) $this->templatePrefix . 'index.html.twig';

        if ($type === null) $type = $this->filterClassName;

        // ---

        $request = $this->requestStack->getCurrentRequest();
        
        $query = $this->listQuery();

        if ($hasFilterForm) {
            $filter_form = $this->createForm(
                $type, $data, $formOptions
            );
 
            $filter_form->handleRequest($request);
    
            $query = $this->filterManager->filter(
                $query, $filter_form, $this->entityClassName, $this->instance
            );
        }

        return $this->render(
            $template,
            [
                'pagination' => $this->paginate($query),
                'filter_form' => ($filter_form !== null)
                    ? $filter_form->createView()
                    : $filter_form,
            ]
        );
    }


    public function customIndex(
        string $type = null,
    ): Response {
        $filter_form = $this->createForm(
            $type,
            null,
            [
                'instance' => $this->instance,
            ]
        );

        $filter_form->handleRequest($this->requestStack->getCurrentRequest());
        $query = $this->filterManager
            ->filter(
                $this->repository->findForInstanceAndGlobal(
                    $this->instance,
                    $this->directory
                ),
                $filter_form,
                $this->entityClassName,
                $this->instance
            );

        return $this->render(
            (string) $this->templatePrefix . 'index.html.twig',
            [
                'pagination' => $query->getQuery()->getResult(),
                'filter_form' => $filter_form->createView(),
                'directory' => $this->directory,
                'instance' => $this->instance,
            ]
        );
    }


    protected function baseShow(
        string $id,
        string $template = null,
    ): Response {
        if ($template === null) 
            $template = (string) $this->templatePrefix . 'index.html.twig';

        // ---

        $entity = $this->findQuery($id);
        if (!$entity) $this->error('entity_not_found');

        return $this->render(
            $template,
            [
                'entity' => $entity,
            ]
        );
    }


    protected function baseNew(
        Entity $entity = null,
        string $type = null,
        array $formOptions = [],
        string $template = null
    ): Response {
        if ($entity === null) {
            $entityClassName = $this->entityClassName;
            $entity = new $entityClassName();
        }
        
        if ($template === null) 
            $template = (string) $this->templatePrefix . 'new.html.twig';

        // ---

        $form = $this->createForm(
            $type, $entity, $formOptions
        );

        return $this->render(
            $template,
            [
                'entity' => $entity,
                'form' => $form->createView(),
            ]
        );
    }


    protected function persistEntity($entity)
    {
        // $this->printVar($entity);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }


    protected function onValidCreateForm(
        $entity,
        FormInterface $form,
        array $options,
        string $route,
        string $template,
        Request $request
    ): void {
        $this->persistEntity($entity);
    }


    protected function baseCreate(
        Entity $entity = null,
        string $type = null,
        array $formOptions = [],
        string $route = null,
        string $template = null
    ): RedirectResponse|Response {
        if ($entity === null) {
            $entityClassName = $this->entityClassName;
            $entity = new $entityClassName();
        }
        
        if ($template === null) 
            $template = (string) $this->templatePrefix . 'new.html.twig';

        // ---
        
        $request = $this->requestStack->getCurrentRequest();
        
        $form = $this->createForm(
            $type, $entity, $formOptions
        );
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $this->onValidCreateForm(
                    $entity, $form, $formOptions, $route, $template, $request
                );

                $this->addEntityFlash(
                    'success', 'The %entity% was successfully created.',
                );

                return $this->redirect(
                    $this->generateUrl(
                        $route
                    )
                );
            } catch (UniqueConstraintViolationException $e) {
                $this->addEntityFlash(
                    'error', 'The %entity% already exists.'
                );
            }
        }

        $this->addEntityFlash(
            'error', 'There were errors creating the %entity%.'
        );

        return $this->render(
            $template,
            [
                'entity' => $entity,
                'form' => $form->createView(),
            ],
        );
    }


    protected function baseEdit(
        string $id,
        string $type = null,
        array $formOptions = [],
        string $route = null,
        string $template = null,
        Entity $entity = null,
        array $extraParams = []
    ): Response {
        if ($template === null) 
            $template = (string) $this->templatePrefix . 'edit.html.twig';

        // ---

        if ($entity === null) {
            $entity = $this->findQuery($id);
            if (!$entity) $this->error('entity_not_found');
        }

        $editForm = $this->createForm(
            $type, $entity, $formOptions
        );

        return $this->render(
            $template,
            [
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'route' => $route,
                ... $extraParams
            ]
        );
    }


    protected function onValidUpdateForm(
        $entity,
        FormInterface $form,
        array $options,
        string $route,
        string $template,
        Request $request
    ): void {
        $this->persistEntity($entity);
    }


    protected function baseUpdate(
        string $id,
        string $route,
        string $type = null,
        array $formOptions = [],
        string $template = null,
        Entity $entity = null
    ): RedirectResponse|Response {
        if ($template === null) 
            $template = (string) $this->templatePrefix . 'edit.html.twig';

        // ---

        if ($entity === null) {
            $entity = $this->findQuery($id);
            if (!$entity) $this->error('entity_not_found');
        }

        $editForm = $this->createForm(
            $type, $entity, $formOptions
        );

        $request = $this->requestStack->getCurrentRequest();

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            try {
                $this->onValidUpdateForm(
                    $entity, $editForm, $formOptions, $route, $template, $request
                );

                $this->addEntityFlash(
                    'success', 'The %entity% was successfully edited.'
                );

                return $this->redirect(
                    $this->generateUrl(
                        $route, ['id' => $id]
                    )
                );
            } catch (UniqueConstraintViolationException $e) {
                $this->addEntityFlash(
                    'error', 'The %entity% already exists.'
                );
            }
        }

        $this->addEntityFlash(
            'error', 'There were errors editing the %entity%.'
        );

        return $this->render(
            $template,
            [
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                ... $formOptions
            ]
        );
    }


    protected function baseDelete(
        string $id,
        string $route
    ): RedirectResponse {
        $form = $this->createDeleteForm($id);
        $request = $this->requestStack->getCurrentRequest();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity = $this->findQuery($id);

            if (!$entity) $this->error('entity_not_found');

            $this->persistEntity($entity);

            $this->addEntityFlash(
                'success', 'The %entity% was successfully deleted.'
            );
        }

        return $this->redirect($this->generateUrl($route));
    }


    protected function baseBatch()
    {
        $request = $this->requestStack->getCurrentRequest();
        $action = $request->get('action');
        $function = 'batch' . ucfirst($action);
        $element_ids = $request->get('element', []);

        return $this->$function($element_ids);
    }

    protected function baseUnion(array $ids): array
    {
        $entities = $this->repository
            ->findBy(['id' => $ids]);

        return [ 'entities' => $entities ];
    }


    protected function mergeSecondaryInstances(BaseUser $main, array $entities) {}


    protected function baseDoUnion($ids, $main_id, $route, $updateInstance = true)
    {
        $main = $this->findQuery($main_id);

        if (!$main) $this->error('entity_not_found');

        $entities = $this->repository
            ->findBaseDoUnionEntities($main, $ids);

        if (count($entities) !== count($ids) - 1)
            $this->error('entity_not_found');

        if ($this->entityClassName === BaseUser::class) {
            $this->mergeSecondaryInstances(
                $main, $entities
            );
        }

        $this->unionManager
            ->union(
                $this->entityClassName, $main,
                $entities, $updateInstance
            );


        $this->addEntityFlash(
            'success', 'The %entities% were successfully joined.', $entities
        );

        return $this->redirect($this->generateUrl($route));
    }


    protected function createDeleteForm($id): FormInterface
    {
        return $this
            ->createFormBuilder([
                'id' => $id,
            ])
            ->add('id', HiddenType::class)
            ->getForm();
    }


    protected function createForm(
        string $type = null,
        $data = null,
        array $options = [],
        bool $hasData = true
    ): FormInterface {
        if ($type === null) $type = $this->typeClassName;

        if ($data === null && $hasData) {
            $entityClassName = $this->entityClassName;
            $data = new $entityClassName();
        }

        return parent::createForm(
            $type, $data, $options
        );
    }

    protected function paginate(
        QueryBuilder $query = null,
        Request $request = null,
        int $page = null,
        int $limit = null,
        array $options = null
    ): PaginationInterface {
        if ($request === null)
            $request = $this->requestStack->getCurrentRequest();

        if ($query === null) $query = $this->listQuery();

        if ($page === null)
            $page = intval($request->query->get('page', 1));

        if ($limit === null) $limit = $this->getResultsPerPage();
        
        if ($options === null) $options = $this->sortDefaults;

        return $this->paginator->paginate(
            $query,
            $page,
            $limit,
            $options
        );
    }
}
