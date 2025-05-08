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

use Celsius3\Entity\BaseUser;
use Celsius3\Form\Type\UserTransformType;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\UserController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Celsius3\Exception\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Admin BaseUser controller.
 */
#[Route("/admin/user")]
final class HtmlAdminBaseUserController extends UserController
{

    public function initialize(): void
    {
        parent::initialize();

        $this->htmlRenderer->setTemplatePrefix('Admin/BaseUser/');

        $this->setSortDefaults([
            'defaultSortFieldName' => 'e.surname',
            'defaultSortDirection' => 'asc',
        ]);

        $this->setInstanceDependent(true);
    }


    public function listQuery(?bool $isInstanceDependent = null): QueryBuilder
    {
        return $this->repository
            ->createQueryBuilder('e')
            ->andWhere('e.instance = :instance_id')
            ->setParameter(
                'instance_id',
                $this->instance->getId()
            );
    }


    /**
     * Lists all BaseUser entities.
     */
    #[Route("/", name: "admin_user")]
    public function htmlIndex(): Response
    {
        return $this->htmlRenderer->render(
            'index',
            $this->index(isInstanceDependent: true)
        );
    }


    /**
     * Finds and displays a BaseUser document.
     * @param string $id The document ID
     * @throws NotFoundHttpException If document doesn't exists
     */
    #[Route("/{id}", name: "admin_user_show", options: ["expose" => true])]
    public function htmlShow(string $id): Response
    {
        return $this->htmlRenderer->render(
            'show',
            params:  $this->show($id)
        );
    }


    /**
     * Displays a form to create a new BaseUser entity.
     */
    #[Route("/new", name: "admin_user_new")]
    public function htmlNew(): Response
    {
        return $this->htmlRenderer->render(
            'new',
            $this->new(formOptions: ['validation_groups' => 'Registration'])
        );
    }


    /**
     * Creates a new BaseUser entity.
     */
    #[Route("/create", name: "admin_user_create", methods: ["POST"])]
    public function htmlCreate(): RedirectResponse|Response
    {
        return $this->htmlRenderer->render(
            'create',
            $this->create(redirectRoute: 'admin_user_new')
        );
    }


    /**
     * Displays a form to edit an existing Country entity.
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    #[Route("/{id}/edit", name: "admin_user_edit", options: ["expose" => true])]
    public function htmlEdit(string $id): Response
    {
        return $this->htmlRenderer->render(
            'edit',
            $this->edit(
                $id,
                formOptions: [ 'editing' => true ]
            )
        );
    }


    /**
     * Updates an existing BaseUser entity.
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function htmlUpdate(
        string $id
    ): RedirectResponse|Response {
        return $this->htmlRenderer->render(
            'edit',
            $this->update(
                $id, 'admin_user_edit',
                formOptions: [ 'editing' => true ]
            )
        );
    }


    /**
     * Enables an existing BaseUser entity.
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    #[Route("/{id}/enable", name: "admin_user_enable", methods: ["PUT"])]
    public function enable(string $id): RedirectResponse
    { return $this->baseEnable($id); }


    // BATCH


    /**
     * Apply a batch function to a group of BaseUser entities.
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    #[Route("/batch", name: "admin_user_batch", methods: ["POST"])]
    public function batch(): mixed
    { return $this->baseBatch(); }


    protected function batchEnable($element_ids): RedirectResponse
    { return $this->baseBatchEnable($element_ids); }


    // UNION


    //  * @Route("/union", name="admin_user_union")
    /**
     * Batch union on a group of BaseUser entities.
     */
    #[Route("/union", name: "admin_user_union", methods: ["POST"])]
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

        if (!$main_user) $this->error(Exception::ENTITY_NOT_FOUND);

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
            $this->error(Exception::ENTITY_NOT_FOUND);

        return $users;
    }


    protected function batchUnion(array $element_ids): Response
    {
        return $this->htmlRenderer->render(
            'batchUnion',
            $this->baseUnion($element_ids)
        );
    }


    // TRANSFORM


    //  * @Route("/transform", name="admin_user_transform")
    /**
     * Transform an instance of BaseUser entity.
     */
    #[Route("/{id}/transform", name: "admin_user_transform", methods: ["GET", "POST"])]

    // SEPARAR EN DOS CONTROLADORES

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
                'admin_user'
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

        return $this->htmlRenderer->render(
            'transform.html.twig',
            $response
        );
    }


    // SWITCH USER


    /**
     * Switch to another instance of BaseUser entity.
     */
    #[
        Route("/switch/{_switch_user}", name: "switch_user"),
        IsGranted("ROLE_ADMIN")
    ]
    public function switch(string $_switch_user): RedirectResponse
    { return $this->switchUser($_switch_user); }
}