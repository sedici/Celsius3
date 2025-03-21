<?php

/*
 * Celsius3 - CRUD Trait
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

namespace Celsius3\Controller\Core;

use Celsius3\Exception\Exception;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;


trait CRUDTrait
{
    public function index(
        ?string $type = null,
        array $formOptions = [],
        $data = null,
        ?FormInterface $filter_form = null,
        ?bool $hasFilterForm = true,
        ?bool $isInstanceDependent = null
    ): array {
        if ($type === null && $hasFilterForm) $type = $this->filterClassName;

        // ---

        $request = $this->requestStack->getCurrentRequest();
        
        $query = $this->listQuery($isInstanceDependent);

        if ($hasFilterForm) {
            $filter_form = $this->createForm(
                $type, $data, $formOptions, $isInstanceDependent
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


    public function show(
        string $id,
        ?bool $isInstanceDependent = null
    ): array|RedirectResponse {
        $entity = $this->findQuery($id, $isInstanceDependent);
        if (!$entity) $this->error(Exception::ENTITY_NOT_FOUND);

        return [ 'entity' => $entity, ];
    }


    // Hook method
    protected function newFormOptions(
        $entity,
        ?string $type = null,
        ?array $formExtraOptions = []
    ): array {
        return [];
    }


    public function new(
        $entity = null,
        ?string $type = null,
        array $formOptions = []
    ): array|RedirectResponse {
        if ($entity === null) {
            $entityClassName = $this->entityClassName;
            $entity = new $entityClassName();
        }

        // ---

        $form = $this->createForm(
            $type, $entity,
            $this->newFormOptions(
                $entity, $type, $formOptions
            )
        );

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }


    // Hook method
    protected function onValidCreateForm(
        $entity,
        FormInterface $form,
        array $formOptions,
        string $redirectRoute,
        Request $request
    ): void {
        $this->persistEntity($entity);
    }


    // Hook method
    protected function createFormOptions(
        ?string $type = null,
        ?string $redirectRoute = null,
        ?array $formExtraOptions = []
    ): array {
        return [];
    }


    public function create(
        $entity = null,
        ?string $type = null,
        array $formOptions = [],
        ?string $redirectRoute = null
    ): array|RedirectResponse {
        if ($entity === null) {
            $entityClassName = $this->entityClassName;
            $entity = new $entityClassName();
        }

        if ($redirectRoute === null)
            $redirectRoute = $this->redirectRoute;

        // ---
        
        $request = $this->requestStack->getCurrentRequest();
        
        $form = $this->createForm(
            $type, $entity,
            $this->createFormOptions(
                $type, $redirectRoute, $formOptions
            )
        );
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $this->onValidCreateForm(
                    $entity, $form, $formOptions, $redirectRoute, $request
                );

                $this->addEntityFlash(
                    'success', 'The %entity% was successfully created.',
                );

                return $this->redirect(
                    $this->generateUrl(
                        $redirectRoute
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

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }


    // Hook method
    protected function editFormOptions(
        $entity,
        ?string $type = null,
        ?string $redirectRoute = null,
        ?bool $isInstanceDependent = null,
        ?array $formExtraOptions = []
    ): array {
        return [];
    }


    public function edit(
        string $id,
        ?string $type = null,
        array $formOptions = [],
        ?string $route = null,
        ?Entity $entity = null,
        array $extraParams = [],
        ?bool $isInstanceDependent = null
    ): array|RedirectResponse {
        if ($entity === null) {
            $entity = $this->findQuery($id, $isInstanceDependent);
            if (!$entity) $this->error(Exception::ENTITY_NOT_FOUND);
        }

        $editForm = $this->createForm(
            $type, $entity,
            $this->editFormOptions(
                $entity, $type, $route, $isInstanceDependent, $formOptions
            )
        );

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'route' => $route,
            ... $extraParams
        ];
    }


    // Hook method
    protected function onValidUpdateForm(
        $entity,
        FormInterface $form,
        array $formOptions,
        string $redirectRoute,
        Request $request
    ): void {
        $this->persistEntity($entity);
    }


    // Hook method
    protected function updateFormOptions(
        $entity,
        ?string $type = null,
        ?string $redirectRoute = null,
        ?bool $isInstanceDependent = null,
        ?array $formExtraOptions = []
    ): array {
        return [];
    }


    public function update(
        string $id,
        ?string $redirectRoute = null,
        ?string $type = null,
        array $formOptions = [],
        ?Entity $entity = null,
        ?bool $isInstanceDependent = null
    ): array|RedirectResponse {
        if ($entity === null) {
            $entity = $this->findQuery($id, $isInstanceDependent);
            if (!$entity) $this->error(Exception::ENTITY_NOT_FOUND);
        }

        if ($redirectRoute === null)
            $redirectRoute = (string) $this->redirectRoute . '_edit';

        // ---

        $editForm = $this->createForm(
            $type, $entity,
            $this->updateFormOptions(
                $entity, $type, $redirectRoute, $isInstanceDependent, $formOptions
            )
        );

        $request = $this->requestStack->getCurrentRequest();

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            try {
                $this->onValidUpdateForm(
                    $entity, $editForm, $formOptions, $redirectRoute, $request
                );

                $this->addEntityFlash(
                    'success', 'The %entity% was successfully edited.'
                );

                return $this->redirect(
                    $this->generateUrl(
                        $redirectRoute, ['id' => $id]
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

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            ... $formOptions
        ];
    }


    public function delete(
        string $id,
        ?string $redirectRoute = null,
        ?bool $isInstanceDependent = null
    ): array|RedirectResponse {
        if ($redirectRoute === null)
            $redirectRoute = $this->redirectRoute;

        // ---

        $form = $this->createDeleteForm($id);
        $request = $this->requestStack->getCurrentRequest();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity = $this->findQuery($id, $isInstanceDependent);

            if (!$entity) $this->error(Exception::ENTITY_NOT_FOUND);

            $this->persistEntity($entity);

            $this->addEntityFlash(
                'success', 'The %entity% was successfully deleted.'
            );
        }

        return $this->redirect($this->generateUrl($this->redirectRoute));
    }
}