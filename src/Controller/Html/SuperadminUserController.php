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

declare(strict_types=1);

namespace Celsius3\Controller\Html;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Instance;
use Celsius3\Form\Type\Filter\BaseUserFilterType;
use Celsius3\Form\Type\UserTransformType;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\BaseUserController;

/**
 * Order controller.
 *
 * @Route("/superadmin/user")
 */
final class SuperadminUserController extends BaseUserController
{

    protected final function getTemplatePrefix(): string
    { return 'Superadmin/BaseUser/'; }


    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.surname',
            'defaultSortDirection' => 'asc',
        ];
    }


    protected function getInstance(): Instance
    { return $this->directory; }


    protected function listQuery(): QueryBuilder
    { return $this->repository->createQueryBuilder('e'); }


    /**
     * Lists all BaseUser entities.
     *
     * @Route("/", name="superadmin_user")
     */
    public function index(): Response
    { return $this->baseInstanceIndex(BaseUserFilterType::class); }


    /**
     * Finds and displays a BaseUser document.
     *
     * @Route("/{id}/show", name="superadmin_user_show")
     *
     * @param string $id The document ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function show(string $id): Response
    {
        return $this->baseShow(
            $id, 'Admin/BaseUser/show.html.twig'
        );
    }


    /**
     * Displays a form to create a new BaseUser entity.
     *
     * @Route("/new", name="superadmin_user_new")
     */
    public function new(): Response
    { return $this->baseInstanceNew(options: ['validation_groups' => 'Registration']); }


    /**
     * Creates a new BaseUser entity.
     *
     * @Route("/create", name="superadmin_user_create", methods={"POST"})
     */
    public function create(): RedirectResponse|Response
    { return $this->baseInstanceCreate(route: 'superadmin_user_new'); }


    /**
     * Displays a form to edit an existing Country entity.
     *
     * @Route("/{id}/edit", name="superadmin_user_edit")
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function edit(string $id): Response
    { return $this->baseEdit($id, formOptions: [ 'editing' => true ]); }


    /**
     * Edits an existing BaseUser entity.
     *
     * @Route("/{id}/update", name="superadmin_user_update", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function update(string $id): RedirectResponse|Response
    {
        return $this->baseUpdate(
            $id, 'superadmin_user_edit',
            formOptions: [ 'editing' => true ]
        );
    }


    /**
     * Enables an existing BaseUser entity.
     *
     * @Route("/{id}/enable", name="superadmin_user_enable", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function enable(string $id): RedirectResponse
    { return $this->baseEnable($id); }


    // BATCH


    // * @Route("/batch", name="superadmin_user_batch")
    /**
     * Apply a batch function to a group of BaseUser entities.
     *
     * @Route("/batch", name="admin_baseuser_batch", methods={"POST"})
     *
     * @param string $id The entity ID
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function batch(): mixed
    {
        return $this->baseBatch();
    }


    protected function batchEnable($element_ids): RedirectResponse
    {
        return $this->baseBatchEnable($element_ids);
    }


    // UNION


    //  * @Route("/union", name="superadmin_user_union")
    /**
     * Batch union on a group of BaseUser entities.
     *
     * @Route("/union", name="superadmin_baseuser_union", methods={"POST"})
     */
    public function union(): RedirectResponse
    {
        $request = $this->requestStack->getCurrentRequest();

        $element_ids = (array) $request->get('element');
        $main_id = $request->get('main');

        $users = $this->doUnion($main_id, $element_ids);

        $this->addEntityFlash(
            'success', 'The %entities% were successfully joined.', $users
        );

        return $this->redirect($this->generateUrl('admin_user'));
    }


    private function doUnion(string $main_id, array $element_ids)
    {
        $main_user = $this->findUser($main_id);
        $users = $this->findUsers($main_user, $element_ids);

        $this->mergeSecondaryInstances($main_user, $users);

        $this->unionManager
            ->union(
                $this->entityClassName,
                $main_user,
                $users,
                false
            );
        return $users;
    }


    private function findUser(string $main_id): BaseUser
    {
        $main_user = $this->findQuery($main_id);

        if (!$main_user) $this->error('entity_not_found');

        return $main_user;
    }


    private function findUsers(BaseUser $main_user, array $element_ids): mixed
    {
        $users = $this->entityManager
            ->getRepository($this->entityClassName)
            ->findBaseDoUnionEntities(
                $main_user,
                $element_ids
            );

        if (count($users) !== count($element_ids) - 1)
            $this->error('entity_not_found');

        return $users;
    }


    protected function batchUnion(array $element_ids): Response
    {
        return $this->render(
            (string) $this->templatePrefix . 'batchUnion.html.twig',
            $this->baseUnion($element_ids)
        );
    }


    // TRANSFORM


    //  * @Route("/transform", name="admin_user_transform")
    /**
     * Transform an instance of BaseUser entity.
     *
     * @Route("/transform", name="superadmin_user_transform", methods={"GET", "POST"})
     */
    public function transform(string $id): array|RedirectResponse|Response
    {
        $request = $this->requestStack->getCurrentRequest();
        $entity = $this->findQuery($id);

        if ($request->getMethod() === 'POST') {
            return $this->baseDoTransform(
                $id,
                UserTransformType::class,
                [
                    'instance' => $this->instance,
                    'user' => $entity,
                    'user_actual' => $this->getUser()
                ],
                'superadmin_user_transform'
            );
        }

        $response = $this->baseTransform(
            $id,
            UserTransformType::class,
            [
                'instance' => $this->instance,
                'user' => $entity,
                'user_actual' => $this->getUser()
            ]
        );

        if ($response instanceof RedirectResponse) {
            return $response;
        }

        return $this->render(
            (string) $this->templatePrefix . 'transform.html.twig',
            $response
        );
    }
}