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
use Celsius3\Entity\Instance;
use Celsius3\Form\Type\BaseUserType;
use Celsius3\Helper\CustomFieldHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

abstract class BaseUserController extends BaseInstanceDependentController
{

    protected CustomFieldHelper $customFieldHelper;
    protected TokenStorageInterface $tokenStorage;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        CustomFieldHelper $custom_field_helper,
        InstanceManager $instanceManager,
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
        ConfigurationHelper $configurationHelper,
        TranslatorInterface $translator,
        ManagerRegistry $managerRegistry,
        RequestStack $requestStack,
        UnionManager $unionManager,
        UserManager $userManager,
        FilterManager $filterManager,
        InstanceHelper $instanceHelper
    ) {
        parent::__construct(
            $instanceManager,
            $entityManager,
            $paginator,
            $configurationHelper,
            $translator,
            $managerRegistry,
            $requestStack,
            $unionManager,
            $userManager,
            $filterManager,
            $instanceHelper
        );

        $this->customFieldHelper = $custom_field_helper;
        $this->tokenStorage = $tokenStorage;
    }

    final protected function getEntity(): string
    { return BaseUser::class; }


    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.surname',
            'defaultSortDirection' => 'asc',
        ];
    }


    protected function baseShow(
        string $id,
        string $template = null
    ): Response {
        if ($template === null) 
            $template = (string) $this->templatePrefix . 'show.html.twig';

        // ---

        $entity = $this->findQuery($id);
        if (!$entity) $this->error('entity_not_found');

        return $this->render(
            $template,
            [
                'element' => $entity,
                'messages' => [],
                'resultsPerPage' => $this->getResultsPerPage()
            ]
        );
    }


    protected function baseTransform(
        string $id,
        string $transformType,
        array $options = []
    ): array|RedirectResponse {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error('entity_not_found');

        if (!$this->getUser()->hasHigherRolesThan($entity)) {
            return $this->redirectToRoute($this->getUserListRoute());
        }

        $transformForm = $this->createForm(
            $transformType, null, $options
        );

        return [
            'entity' => $entity,
            'transform_form' => $transformForm->createView(),
            'route' => null,
        ];
    }


    protected function baseDoTransform(
        $id, $transformType, array $options, $route
    ): array|RedirectResponse {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error('entity_not_found');

        $transformForm = $this->createForm(
            $transformType, null, $options
        );

        $request = $this->requestStack->getCurrentRequest();

        $transformForm->handleRequest($request);

        if ($transformForm->isValid()) {
            $data = $transformForm->getData();
            $this->userManager->transform(
                $data[ $entity->getInstance()->getUrl() ],
                $entity
            );

            foreach ($entity->getSecondaryInstances() as $key => $value) {
                $instance = $this->objectManager
                    ->getRepository(Instance::class)->find($key);

                if (array_key_exists($instance->getUrl(), $data)) {
                    $entity->addSecondaryInstance($instance, $data[$instance->getUrl()]);
                }
            }

            $this->persistEntity($entity);

            $this->addFlash(
                'success', 'The User was successfully transformed.'
            );

            return $this->redirect(
                $this->generateUrl(
                    $route, [ 'id' => $id ]
                )
            );
        }

        $this->addEntityFlash(
            'error', 'There were errors editing the %entity%.'
        );

        return [
            'entity' => $entity,
            'edit_form' => $transformForm->createView(),
        ];
    }


    protected function baseEnable(string $id): RedirectResponse
    {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error('entity_not_found');

        $this->enableUser($entity);

        return $this->redirect(
            $this->requestStack
                ->getCurrentRequest()->headers
                ->get('referer')
        );
    }


    protected function enableUser(BaseUser $user)
    {
        $user->setEnabled(true);
        $this->persistEntity($user);
    }


    protected function baseBatchEnable(array $element_ids)
    {
        $users = $this->repository->findUsers($element_ids);

        foreach ($users as $user) {
            $this->enableUser($user);
        }

        return $this->redirect(
            $this->requestStack
                ->getCurrentRequest()->headers
                ->get('referer')
        );
    }


    protected function mergeSecondaryInstances(BaseUser $main, array $entities)
    {
        foreach ($entities as $entity) {
            if ($main->getInstance() === $entity->getInstance()) {
                $main->setRoles(array_unique(
                    array_merge($main->getRoles(), $entity->getRoles())
                ));
            } else if ($main->hasSecondaryInstance($entity->getInstance())) {
                $main->addSecondaryInstance(
                    $entity->getInstance(),
                    array_unique(
                        array_merge(
                            $main->getSecondaryInstances()[$entity->getId()]['roles'],
                            $entity->getRoles()
                        )
                    )
                );
            } else {
                $main->addSecondaryInstance(
                    $entity->getInstance(),
                    $entity->getRoles()
                );
            }

            foreach ($entity->getSecondaryInstances() as $id => $secondaryInstance) {
                $instance = $this->objectManager
                    ->getRepository(Instance::class)->find($id);

                if ($main->getInstance() === $instance) {
                    $main->setRoles(array_unique(
                        array_merge(
                            $main->getRoles(),
                            $secondaryInstance['roles']
                        )
                    ));
                } else if ($main->hasSecondaryInstance($instance)) {
                    $main->addSecondaryInstance(
                        $instance,
                        array_unique(array_merge(
                            $main->getSecondaryInstances()[(int)$instance->getId()]['roles'],
                            $secondaryInstance['roles']
                        ))
                    );
                } else {
                    $main->addSecondaryInstance(
                        $instance,
                        $secondaryInstance['roles']
                    );
                }
            }
        }

        $this->persistEntity($main);
    }


    public function findOneForInstanceByUsername(string $username)
    {
        return $this->repository->createQueryBuilder('e')
            ->andWhere('e.instance = :instance_id')
            ->andWhere('e.id = :id')
            ->setParameter('instance_id', $this->instance->getId())
            ->setParameter('username', $username)
            ->getQuery()->getOneOrNullResult();
    }

    protected function switchUser(string $username): RedirectResponse
    {
        if (!$this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->findOneForInstanceByUsername($username);

            $token = new UsernamePasswordToken(
                $user, 'secured_area', $user->getRoles()
            );

            $this->tokenStorage->setToken($token);
        }

        return $this->redirectToRoute('user_index');
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

        $this->customFieldHelper->processCustomUserFields(
            $this->instance, $form, $entity
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

        $this->customFieldHelper->processCustomUserFields(
            $this->instance, $form, $entity
        );
    }
}
