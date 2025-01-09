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

use Celsius3\Entity\Journal;
use Celsius3\Entity\Order;
use Celsius3\Form\Type\JournalType;
use Celsius3\Form\Type\JournalTypeType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class OrderController extends BaseInstanceDependentController
{

    final protected function getEntity(): string
    { return Order::class; }


    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.updatedAt',
            'defaultSortDirection' => 'desc'
        ];
    }


    protected function getMaterialTypeClassName(string $short_name): string {
        return 'Celsius3\\Form\\Type\\' . ucfirst($short_name) . 'TypeType';
    }


    protected function getMaterialClassName(string $short_name): string {
        return 'Celsius3\\Form\\Type\\' . ucfirst($short_name) . 'Type';
    }


    protected function onValidCreateForm(
        $entity,
        FormInterface $form,
        array $options,
        string $route,
        string $template,
        Request $request
    ): void {
        if ($this->getMaterialType() === JournalTypeType::class) {
            $journal = $this->managerRegistry->getManager()
                ->getRepository(Journal::class)->find(
                    $request->request
                        ->get('order', null)['materialData']['journal']
                );
    
            if ($journal === null) {
                $entity->getMaterialData()->setOther(
                    $request->request->get(
                        'order', null
                    )['materialData']['journal_autocomplete']
                );
                
                $entity->getMaterialData()->setJournal(null);
            }
        }

        $this->persistEntity($entity);
    }


    // protected function baseCreateOrderLogic(
    //     $entity /* Order con material de tipo Journal */,
    //     string $type,
    //     array $options,
    //     string $route
    // ): array|RedirectResponse {
    //     $request = $this->requestStack->getCurrentRequest();

    //     $form = $this->createForm($type, $entity, $options);
    //     $form->handleRequest($request);

    //     if ($form->isValid()) {
    //         if ($this->getMaterialType() === JournalTypeType::class) {
    //             $journal = $this->managerRegistry->getManager()
    //                 ->getRepository(Journal::class)->find(
    //                     $request->request
    //                         ->get('order', null)['materialData']['journal']
    //                 );

    //             if ($journal === null) {
    //                 $entity->getMaterialData()->setOther(
    //                     $request->request->get(
    //                         'order', null
    //                     )['materialData']['journal_autocomplete']
    //                 );
                    
    //                 $entity->getMaterialData()->setJournal(null);
    //             }
    //         }

    //         $this->persistEntity($entity);
    //         $this->addEntityFlash(
    //             'success', 'The %entity% was successfully created.'
    //         );

    //         if ($form->has('save_and_show')) {
    //             $saveNShow = $form->get('save_and_show');
    //             if (
    //                 $saveNShow instanceof SubmitButton
    //                 && $saveNShow->isClicked()
    //             ) {
    //                 return $this->redirect($this->generateUrl(
    //                     'admin_order_show',
    //                     [ 'id' => $entity->getId()]
    //                 ));
    //             }
    //         }

    //         return $this->redirect($this->generateUrl($route));
    //     }

    //     $this->addEntityFlash(
    //         'error', 'There were errors creating the %entity%.'
    //     );

    //     return [
    //         'entity' => $entity,
    //         'form' => $form->createView(),
    //     ];
    // }


    protected function change(): Response
    {
        $request = $this->requestStack->getCurrentRequest();

        $materialClassName= $this->getMaterialTypeClassName(
            $request->get('material')
        );

        if (!class_exists($materialClassName)) {
            $this->createNotFoundException('Inexistent Material Type');
        }

        $form = $this->createForm(options: [
            'material' => $materialClassName,
            'actual_user' => $this->getUser(),
            'instance' => $this->instance,
            'user' => $this->getUser()
        ]);

        return $this->render(
            (string) $this->entityClassShortName . '/_materialData.html.twig',
            [
                'form' => $form->createView(),
                'material' => $request->get('material')
            ]
        );
    }


    protected function getMaterialType($materialData = null): string
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($materialData === null) {
            $materialTypeName = $this->getMaterialTypeClassName(
                $request->request->get(
                    'order', null
                )['materialDataType']
            );
        } else {
            $class = explode('\\', $materialData);
            $materialTypeName = $this
                ->getMaterialClassName(end($class));
        }

        return $materialTypeName;
    }
}
