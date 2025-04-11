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

namespace Celsius3\Controller\Html;

use Celsius3\Controller\Base\DataRequestController;
use Celsius3\Entity\DataRequest;
use Celsius3\Entity\OrdersDataRequest;
use Celsius3\Entity\UsersDataRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route("/admin/data-request")]
class HtmlDataRequestController extends DataRequestController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->htmlRenderer->setTemplatePrefix('Admin/DataRequest/');
    }


    #[Route("/orders", name: 'admin_instance_orders_data_request')]
    public function ordersDataRequest(): Response
    {
        return $this->dataRequestController(
            OrdersDataRequest::class
        );
    }


    #[Route("/users", name: 'admin_instance_users_data_request')]
    public function usersDataRequest(): Response
    {
        return $this->dataRequestController(
            UsersDataRequest::class
        );
    }


    protected function dataRequestController(
        string $drClass,
        ?string $type = null,
        ?string $drField = null,
        ?string $templateName = null
    ): Response {
        $class = new \ReflectionClass($drClass);
        $className = $class->getShortName();

        if ($type === null) $type = (string) 'Celsius3\\Form\\Type\\' . $className . 'Type';
        if ($drField === null) $drField = $this->toSnakeCase($className);
        if ($templateName === null) $templateName = $this->toSnakeCase($className);

        $request = $this->requestStack->getCurrentRequest();

        $data_request = $this->createDataRequest(
            $request->get($drField)
        );

        $form = $this->createForm($type, $data_request);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->save($data_request);

            $this->addFlash(
                'success', $this->translator->trans(
                    'The data request was successfully registered',
                    [],
                    'Flashes'
                )
            );
        }

        return $this->htmlRenderer->render(
            $templateName,
            ['form' => $form->createView()]
        );
    }


    protected function toSnakeCase(string $name): string
    {
        return strtolower(
            preg_replace(
                '/(?<!^)[A-Z]/',
                '_$0',
                $name
            )
        );
    }


    protected function createDataRequest(?array $dr): DataRequest
    {
        $data_request = new OrdersDataRequest(
            $this->instanceHelper->getSessionInstance()
        );

        $data = null;
        if ($dr) {
            foreach ($dr as $k => $v) {
                if ($v === '1') {
                    $data[] = $k;
                } elseif (is_array($v) && !empty($v)) {
                    $data[] = [$k => $v];
                }
            }
        }

        if ($data) $data_request->setData(serialize($data));

        return $data_request;
    }
}