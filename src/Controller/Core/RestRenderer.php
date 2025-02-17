<?php

/*
 * Celsius3 - Rest renderer interface
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

use Celsius3\Entity\Instance;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

class RestRenderer extends BaseRenderer
{

    public function __construct(
        protected EntityManagerInterface $entityManager,
        ViewHandlerInterface $viewHandler,
        Environment $twig
    ) {
        parent::__construct($viewHandler, $twig);
    }


    public function render(
        $data = null,
        int $statusCode = Response::HTTP_OK,
        array|string $serializerGroups = []
    ): Response {
        if ($data === null) throw new \Exception('data is required');

        $view = $this->view(
            $data, $statusCode,
        )->setFormat('json');

        if ($serializerGroups) {
            $context = new Context();
            if (is_array($serializerGroups)) {
                $context->addGroups($serializerGroups);
            }
            if (is_string($serializerGroups)) {
                $context->addGroup($serializerGroups);
            }
            $view->setContext($context);
        }

        return $this->viewHandler->handle($view);
    }


    protected function view($data = null, ?int $statusCode = null, array $headers = []): View
    { return View::create($data, $statusCode, $headers); }


    public function index(
        array|string $serializerGroups = null
    ): Response {
        return $this->render(
            data: array_values($this->controller->listQuery()->getQuery()->execute()),
            serializerGroups: $serializerGroups
        );
    }


    public function show(
        string $id,
        array|string $serializerGroups = null
    ): Response {
        $query = $this->controller->findQuery($id);
        if (!$query) $this->controller->error('entity_not_found');
        return $this->render(data: $query, serializerGroups: $serializerGroups);
    }

    protected function validateAjax(
        string $target, array $allowed_targets
    ): bool {
        return in_array(
            $target, $allowed_targets, true
        );
    }


    protected function getRepository(string $target): EntityRepository
    {
        $repository = $this->entityManager
            ->getRepository((string) 'Celsius3\\Entity\\' . $target);
        if (!$repository) throw new NotFoundHttpException('Repository not found - Incorrect target');
        return $repository;
    }


    public function ajax(
        Request $request,
        array $allowedTargets,
        Instance $instance = null
    ): Response {
        if (!$request->isXmlHttpRequest())
            throw new BadRequestHttpException('The request hast to be an AJAX request');

        $target = $request->get('target');
        if (!$this->validateAjax($target, $allowedTargets))
            throw new BadRequestHttpException('The target is not allowed');

        $term = $request->get('term');

        $result = $this->getRepository($target)
            ->findByTerm($term, $instance, null)
            ->getResult();

        $json = [];

        foreach ($result as $element) {
            $json[] = (method_exists($element, 'asJson'))
                ? $element->asJSon()
                : [
                    'id' => $element->getId(),
                    'value' => ($target === 'BaseUser')
                        ? $element->__toString() . ' (' . $element->getUsername() . ')'
                        : $element->__toString(),
                ];
        }

        return $this->render(data: $json);
    }
}