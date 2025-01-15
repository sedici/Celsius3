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
use Doctrine\ORM\Mapping\Entity;
use ReflectionClass;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class HtmlEntityController extends EntityController
{

    protected string|null $templatePrefix;


    public function initialize(): void
    {
        parent::initialize();
        $this->templatePrefix = $this->getTemplatePrefix();
    }


    protected function getTemplatePrefix(): string
    {
        $str = (new ReflectionClass($this))->getShortName();

	    $str = preg_replace(
            '/Controller$/', '', $str
        );
	
	    $str = preg_replace(
            '/([a-z])([A-Z])/', '$1/$2', $str
        ) . '/';

        return $str;
    }


    public function htmlIndex(
        ?string $type = null,
        array $formOptions = [],
        $data = null,
        FormInterface $filter_form = null,
        ?bool $hasFilterForm = true,
        ?bool $isInstanceDependent = null,
        string $templatePostfix = 'index'
    ): Response {
        $parameters = $this->index(
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


    public function htmlShow(
        string $id,
        ?bool $isInstanceDependent = null,
        string $templatePostfix = 'show'
    ): Response {
        $parameters = $this->show(
            $id,
            $isInstanceDependent
        );

        if ($parameters instanceof RedirectResponse) return $parameters;

        return $this->render(
            (string) $this->templatePrefix . $templatePostfix . '.html.twig',
            $parameters
        );
    }


    public function htmlEdit(
        string $id,
        ?string $type = null,
        array $formOptions = [],
        ?string $route = null,
        ?Entity $entity = null,
        array $extraParams = [],
        string $templatePostfix = 'edit'
    ): Response {
        $parameters = $this->edit(
            $id,
            $type,
            $formOptions,
            $route,
            $entity,
            $extraParams
        );

        if ($parameters instanceof RedirectResponse) return $parameters;

        return $this->render(
            (string) $this->templatePrefix . $templatePostfix . '.html.twig',
            $parameters
        );
    }


    public function htmlUpdate(
        string $id,
        string $redirectRoute = null,
        ?string $type = null,
        array $formOptions = [],
        ?Entity $entity = null,
        string $templatePostfix = 'update'
    ): Response {
        $parameters = $this->update(
            $id,
            $redirectRoute,
            $type,
            $formOptions,
            $entity
        );

        if ($parameters instanceof RedirectResponse) return $parameters;

        return $this->render(
            (string) $this->templatePrefix . $templatePostfix . '.html.twig',
            $parameters
        );
    }


    public function htmlNew(
        ?Entity $entity = null,
        ?string $type = null,
        array $formOptions = [],
        string $templatePostfix = 'new'
    ): Response {
        $parameters = $this->new(
            $entity, $type, $formOptions
        );

        if ($parameters instanceof RedirectResponse) return $parameters;

        return $this->render(
            (string) $this->templatePrefix . $templatePostfix . '.html.twig',
            $parameters
        );
    }


    public function htmlCreate(
        ?Entity $entity = null,
        ?string $type = null,
        array $formOptions = [],
        ?string $redirectRoute = null,
        string $templatePostfix = 'create'
    ): Response {
        $parameters = $this->create(
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


    public function htmlDelete(
        string $id,
        string $redirectRoute = null,
        string $templatePostfix = 'delete'
    ): Response {
        $parameters = $this->delete(
            $id, $redirectRoute
        );

        if ($parameters instanceof RedirectResponse) return $parameters;

        return $this->render(
            (string) $this->templatePrefix . $templatePostfix . '.html.twig',
            $parameters
        );
    }
}