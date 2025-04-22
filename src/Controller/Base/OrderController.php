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

use Celsius3\Controller\Core\EntityController;
use Celsius3\Entity\Journal;
use Celsius3\Entity\Order;
use Celsius3\Form\Type\JournalTypeType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class OrderController extends EntityController
{

    public function initialize(): void
    {
        $this->setEntity(Order::class);
        parent::initialize();
        $this->setInstanceDependent(true);
        $this->setSortDefaults([
            'defaultSortFieldName' => 'e.updatedAt',
            'defaultSortDirection' => 'desc'
        ]);
    }


    protected function getMaterialTypeClassName(string $short_name): string {
        return 'Celsius3\\Form\\Type\\' . ucfirst($short_name) . 'TypeType';
    }


    protected function getMaterialClassName(string $short_name): string {
        return 'Celsius3\\Form\\Type\\' . ucfirst($short_name) . 'Type';
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

        // throw new \Exception((string)var_dump($formOptions));

        $form = $this->createForm(
            $type, $entity,
            $this->createFormOptions(
                $type, $redirectRoute, $formOptions,
            )
        );
        $form->handleRequest($request);

        // throw new \Exception((string)var_dump($entity));
        // throw new \Exception((string)var_dump($request->request->all()));

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


    protected function onValidCreateForm(
        $entity,
        FormInterface $form,
        array $formOptions,
        string $redirectRoute,
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

        $originalRequest = $entity->getOriginalRequest();

        $originalRequest->setCreator($this->getUser());

        try { $originalRequest->getOwner(); }
        catch (\Throwable $e) {
            $originalRequest->setOwner($this->getUser());
        }

        $this->persistEntity($entity);
    }


    protected function change(
        ?string $templateName = null,
        ?string $templatePrefix = null
    ): Response {
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

        return $this->htmlRenderer->render(
            $templateName ?? '_materialData',
            [
                'form' => $form->createView(),
                'material' => $request->get('material')
            ],
            $templatePrefix ?? 'Order/'
        );
    }


    protected function getMaterialType($materialData = null): string
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($materialData === null) {
            $materialTypeName = $this->getMaterialTypeClassName(
                $request->get(
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
