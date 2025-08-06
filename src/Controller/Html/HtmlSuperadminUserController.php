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
use Celsius3\Form\Type\Filter\BaseUserFilterType;
use Celsius3\Form\Type\UserTransformType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Controller\Base\UserController;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Celsius3\Exception\Exception;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


#[
    Route('/superadmin/user'),
    IsGranted('ROLE_SUPER_ADMIN')
]
final class HtmlSuperadminUserController extends UserController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->htmlRenderer->setTemplatePrefix('Superadmin/BaseUser/');
        $this->setInstanceDependent(false);
        $this->setInstance($this->directory);
    }


    #[
        Route(
            '/',
            name: 'superadmin_user',
            methods: ['GET']
        )
    ]
    public function htmlIndex(): Response
    {
        return $this->htmlRenderer->render(
            'index',
            $this->index(
                BaseUserFilterType::class
                // 'superadmin_user'
            )
        );
    }


    #[Route(
        '/{id}',
        name: 'superadmin_user_show',
        methods: ['GET']
    )]
    public function htmlShow(string $id): Response
    {
        return $this->htmlRenderer->render(
            'show',
            $this->show($id)
        );
    }

    
    #[Route(
        '/new',
        name: 'superadmin_user_new',
        methods: ['GET']
    )]
    public function htmlNew(): Response
    {
        return $this->htmlRenderer->render(
            'new',
            $this->new(
                formOptions: ['validation_groups' => 'Registration']
                // isInstanceDependent: true // tiene sentido?
            )
        );
    }


    #[Route(
        '/create',
        name: 'superadmin_user_create',
        methods: ['POST']
    )]
    public function htmlCreate(): RedirectResponse|Response
    {
        return $this->htmlRenderer->render(
            'create',
            $this->create(
                redirectRoute: 'superadmin_user_new',
                // isInstanceDependent: true // tiene sentido?
            )
        );
    }


    #[Route(
        '/{id}/edit',
        name: 'superadmin_user_edit',
        methods: ['GET']
    )]
    public function htmlEdit(string $id): Response
    {
        return $this->htmlRenderer->render(
            'edit',
            $this->edit(
                $id, formOptions: [ 'editing' => true ]
            )
        );
    }


    #[Route(
        '/{id}/update',
        name: 'superadmin_user_update',
        methods: ['POST']
    )]
    public function htmlUpdate(string $id): RedirectResponse|Response
    {
        return $this->htmlRenderer->render(
            'update',
            $this->update(
                $id, 'superadmin_user_edit',
                formOptions: [ 'editing' => true ]
            )
        );
    }


    #[Route(
        '/{id}/enable',
        name: 'superadmin_user_enable',
        methods: ['POST']
    )]
    public function enable(string $id): RedirectResponse
    { return $this->baseEnable($id); }


    // BATCH
    // * @Route("/batch", name="superadmin_user_batch")
    /**
     * Apply a batch function to a group of BaseUser entities.
     * @param string $id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    #[Route('/batch', name: 'admin_baseuser_batch', methods: ['POST'])]
    public function batch(): mixed
    { return $this->baseBatch(); }


    protected function batchEnable($element_ids): RedirectResponse
    { return $this->baseBatchEnable($element_ids); }


    // UNION


    #[Route(
        '/union',
        name: 'superadmin_user_union',
        methods: ['POST']
    )]
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
        $main_user = $this->findQuery($main_id);
        if (!$main_user) $this->error(Exception::ENTITY_NOT_FOUND);

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


    private function findUsers(BaseUser $main_user, array $element_ids): mixed
    {
        $users = $this->repository->findBaseDoUnionEntities($main_user, $element_ids);

        if (count($users) !== count($element_ids) - 1)
            $this->error(Exception::ENTITY_NOT_FOUND);

        return $users;
    }


    protected function batchUnion(array $ids): Response
    {
        return $this->htmlRenderer->render(
            'batchUnion',
            [ 'entities' => $this->repository->findBy(['id' => $ids]) ]
        );
    }


    // TRANSFORM


    #[Route(
        '/{id}/transform',
        name: 'superadmin_user_transform',
        methods: ['GET', 'POST']
    )]
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

        return $this->htmlRenderer->render(
            'transform',
            $response
        );
    }
}