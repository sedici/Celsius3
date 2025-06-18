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
use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Instance;
use Celsius3\Entity\Thread;
use Celsius3\EntityManager\ThreadManager;
use Celsius3\Helper\CustomFieldHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Celsius3\Exception\Exception;

use Celsius3\Controller\Core\HtmlRenderer;
use Celsius3\Controller\Core\RestRenderer;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Helper\InstanceHelper;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Celsius3\Repository\InstanceRepository;
use Celsius3\Repository\ThreadRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UserController extends EntityController
{
    protected ThreadRepository $threadRepository;
    protected InstanceRepository $instanceRepository;

    public function __construct(
        protected TokenGeneratorInterface $tokenGenerator,
        protected ThreadManager $threadManager,
        protected CustomFieldHelper $customFieldHelper,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
        ConfigurationHelper $configurationHelper,
        TranslatorInterface $translator,
        ManagerRegistry $managerRegistry,
        RequestStack $requestStack,
        UnionManager $unionManager,
        UserManager $userManager,
        FilterManager $filterManager,
        InstanceHelper $instanceHelper,
        FormFactoryInterface $formFactory,
        SessionInterface $session,
        RouterInterface $router,
        TokenStorageInterface $tokenStorage,
        Security $security,
        HtmlRenderer $htmlRenderer,
        RestRenderer $restRenderer
    ) {
        parent::__construct(
            $validator,
            $entityManager,
            $paginator,
            $configurationHelper,
            $translator,
            $managerRegistry,
            $requestStack,
            $unionManager,
            $userManager,
            $filterManager,
            $instanceHelper,
            $formFactory,
            $session,
            $router,
            $tokenStorage,
            $security,
            $htmlRenderer,
            $restRenderer
        );
    }


    public function initialize(): void
    {
        $this->setEntity(BaseUser::class);

        parent::initialize();

        $this->setSortDefaults([
            'defaultSortFieldName' => 'e.surname',
            'defaultSortDirection' => 'asc',
        ]);

        $this->threadRepository = $this->entityManager->getRepository(Thread::class);
        $this->instanceRepository = $this->entityManager->getRepository(Instance::class);
    }


    public function listQuery(bool|null $isInstanceDependent = null): QueryBuilder
    {
        if (!$isInstanceDependent && !$this->isInstanceDependent)
            return parent::listQuery($isInstanceDependent);

        return $this->repository->createQueryBuilder('e')
            ->andWhere('e.instance = :instance_id')
            ->setParameter('instance_id', $this->instance->getId());
    }


    public function getUserListRoute(): string
    { return 'user_index'; }


    protected function baseTransform(
        string $id,
        string $transformType,
        array $options = []
    ): array|RedirectResponse {
        $entity = $this->findQuery($id);

        if (!$entity) $this->error(Exception::ENTITY_NOT_FOUND);

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

        if (!$entity) $this->error(Exception::ENTITY_NOT_FOUND);

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
                $instance = $this->entityManager
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

        if (!$entity) $this->error(Exception::ENTITY_NOT_FOUND);

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


    protected function mergeSecondaryInstances(BaseUser $main, array $entities): void
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
                $instance = $this->entityManager
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
        if (!$this->security->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
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
        array $formOptions,
        string $redirectRoute,
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
        array $formOptions,
        string $redirectRoute,
        Request $request
    ): void {
        $this->persistEntity($entity);

        $this->customFieldHelper->processCustomUserFields(
            $this->instance, $form, $entity
        );
    }
}
