<?php

declare(strict_types=1);

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

namespace Celsius3\Controller\User\Order;

use Celsius3\Entity\Order;
use Celsius3\Form\Type\OrderType;
use Celsius3\Helper\InstanceHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Celsius3\Controller\BaseInstanceDependentController;


/**
 * User order controller.
 *
 * @Route("/user/order")
 */
final class ChangeMaterialTypeGetController extends BaseInstanceDependentController
{

    protected final function getEntity(): string
    { return Order::class; }

    protected final function getType(): string
    { return OrderType::class; }

    protected final function getTemplatePrefix(): string
    { return 'Order/'; }


    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.updatedAt',
            'defaultSortDirection' => 'asc',
        ];
    }


    public function __invoke(Request $request)
    {
        $material = $this->materialTypeClass($request);

        $entityClassName = $this->entityClassName;

        $form = $this->createForm(
            $this->typeClassName,
            new $entityClassName(),
            [
                'instance' => $this->instance,
                'material' => $material,
                'actual_user' => $this->getUser(),
            ]
        );

        return $this->render(
            (string) $this->templatePrefix . '_materialData.html.twig',
            [
                'form' => $form->createView(),
                'material' => $request->get('material'),
            ]
        );
    }

    private function materialTypeClass(Request $request)
    {
        $material = 'Celsius3\\Form\\Type\\' . ucfirst($request->get('material')) . 'TypeType';

        if (!class_exists($material)) {
            $this->createNotFoundException('Inexistent Material Type');
        }

        return $material;
    }
}
