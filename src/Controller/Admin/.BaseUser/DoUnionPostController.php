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

namespace Celsius3\Controller\Admin\BaseUser;

use Celsius3\Controller\BaseUserController;
use Celsius3\Entity\BaseUser;
use Celsius3\Manager\UnionManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

final class DoUnionPostController extends BaseUserController
{

    /**
     * @var UnionManager
     */
    protected $unionManager;

    public function __construct(
        UnionManager $unionManager,
        ... $args
    ) {
        parent::__construct(... $args);
        $this->unionManager = $unionManager;
    }

    protected final function getTemplatePrefix(): string
    { return 'Admin/BaseUser/'; }


    public function __invoke(Request $request): RedirectResponse
    {
        $element_ids = (array) $request->request->get('element');
        $main_id = $request->request->get('main');

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
                BaseUser::class,
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
            ->getRepository(BaseUser::class)
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
}
