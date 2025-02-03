<?php

/*
 * Celsius3 - Base HTML entity controller
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

namespace Celsius3\Controller\Html;

use Celsius3\Controller\Core\EntityController;
use Celsius3\Controller\Core\HtmlCrudControllerInterface;
use Doctrine\ORM\Mapping\Entity;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class HtmlEntityController
    extends AbstractController
    implements HtmlCrudControllerInterface
{

    protected string|null $templatePrefix;
    protected EntityController $entityController;


    public function __construct(
        EntityController $entityController,
        ?string $templatePrefix = null
    ) {
        $this->entityController = $entityController;
        $this->templatePrefix = ($templatePrefix === null) 
            ? $this->getTemplatePrefix()
            : $templatePrefix;
    }


    public function getTemplatePrefix(): string
    {
        $str = (new ReflectionClass($this->entityController))->getShortName();

	    $str = preg_replace(
            '/Controller$/', '', $str
        );
	
	    $str = preg_replace(
            '/([a-z])([A-Z])/', '$1/$2', $str
        ) . '/';

        return $str;
    }


    public function index(
        ?string $type = null,
        array $formOptions = [],
        $data = null,
        FormInterface $filter_form = null,
        ?bool $hasFilterForm = true,
        ?bool $isInstanceDependent = null,
        string $templatePostfix = 'index'
    ): array|RedirectResponse {
        $parameters = $this->entityController->index(
            $type,
            $formOptions,
            $data,
            $filter_form,
            $hasFilterForm,
            $isInstanceDependent
        );

        if ($parameters instanceof RedirectResponse) return $parameters;

        return $this->render(
            (string) $this->templatePrefix . $templatePostfix . '.html.twig',
            $parameters
        );
    }


    public function show(
        string $id,
        ?bool $isInstanceDependent = null,
        string $templatePostfix = 'show',
    ): array|RedirectResponse {
        $parameters = $this->entityController->show(
            $id,
            $isInstanceDependent
        );

        if ($parameters instanceof RedirectResponse) return $parameters;

        return $this->render(
            (string) $this->templatePrefix . $templatePostfix . '.html.twig',
            $parameters
        );
    }


    public function edit(
        string $id,
        ?string $type = null,
        array $formOptions = [],
        ?string $route = null,
        ?Entity $entity = null,
        array $extraParams = [],
        ?bool $isInstanceDependent = null,
        string $templatePostfix = 'edit'
    ): array|RedirectResponse {
        $parameters = $this->entityController->edit(
            $id,
            $type,
            $formOptions,
            $route,
            $entity,
            $extraParams,
            $isInstanceDependent
        );

        if ($parameters instanceof RedirectResponse) return $parameters;

        return $this->render(
            (string) $this->templatePrefix . $templatePostfix . '.html.twig',
            $parameters
        );
    }


    public function update(
        string $id,
        string $redirectRoute = null,
        ?string $type = null,
        array $formOptions = [],
        ?Entity $entity = null,
        ?bool $isInstanceDependent = null,
        string $templatePostfix = 'update'
    ): array|RedirectResponse {
        $parameters = $this->entityController->update(
            $id,
            $redirectRoute,
            $type,
            $formOptions,
            $entity,
            $isInstanceDependent
        );

        if ($parameters instanceof RedirectResponse) return $parameters;

        return $this->render(
            (string) $this->templatePrefix . $templatePostfix . '.html.twig',
            $parameters
        );
    }


    public function new(
        ?Entity $entity = null,
        ?string $type = null,
        array $formOptions = [],
        string $templatePostfix = 'new'
    ): array|RedirectResponse {
        $parameters = $this->entityController->new(
            $entity, $type, $formOptions
        );

        if ($parameters instanceof RedirectResponse) return $parameters;

        return $this->render(
            (string) $this->templatePrefix . $templatePostfix . '.html.twig',
            $parameters
        );
    }


    public function create(
        ?Entity $entity = null,
        ?string $type = null,
        array $formOptions = [],
        ?string $redirectRoute = null,
        string $templatePostfix = 'create'
    ): array|RedirectResponse {
        $parameters = $this->entityController->create(
            $entity,
            $type,
            $formOptions,
            $redirectRoute
        );

        if ($parameters instanceof RedirectResponse) return $parameters;

        return $this->render(
            (string) $this->templatePrefix . $templatePostfix . '.html.twig',
            $parameters
        );
    }


    public function delete(
        string $id,
        string $redirectRoute = null,
        string $templatePostfix = 'delete'
    ): array|RedirectResponse {
        $parameters = $this->entityController->delete(
            $id, $redirectRoute
        );

        if ($parameters instanceof RedirectResponse) return $parameters;

        return $this->render(
            (string) $this->templatePrefix . $templatePostfix . '.html.twig',
            $parameters
        );
    }
}