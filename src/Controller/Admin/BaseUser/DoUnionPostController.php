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

use Celsius3\CoreBundle\Controller\BaseUserController;
use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Exception\Exception;
use Celsius3\CoreBundle\Manager\UnionManager;
use Doctrine\ORM\EntityManagerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Translator;

final class DoUnionPostController extends BaseUserController
{
    private $entityManager;
    private $unionManager;
    private $translator;

    /**
     * @DI\InjectParams({
     *     "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     *     "unionManager" = @DI\Inject("celsius3_core.union_manager"),
     *     "translator" = @DI\Inject("translator"),
     * })
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UnionManager $unionManager,
        Translator $translator
    ) {
        $this->entityManager = $entityManager;
        $this->unionManager = $unionManager;
        $this->translator = $translator;
    }

    public function __invoke(Request $request)
    {
        $element_ids = $request->request->get('element');
        $main_id = $request->request->get('main');

        $users = $this->doUnion($main_id, $element_ids);

        $this->addFlash(
            'success',
            $this->translator->trans(
                'The %entities% were successfully joined.',
                ['%entities%' => $this->translator->transChoice('BaseUser', count($users), [], 'Flashes')]
            )
        );

        return $this->redirect($this->generateUrl('admin_user'));
    }

    private function doUnion($main_id, $element_ids)
    {
        $main_user = $this->findUser($main_id);
        $users = $this->findUsers($main_user, $element_ids);

        $this->mergeSecondaryInstances($main_user, $users);

        $this->unionManager
            ->union($this->getBundle().':BaseUser', $main_user, $users, false);
        return $users;
    }

    private function findUser($main_id)
    {
        $main_user = $this->entityManager->getRepository(BaseUser::class)->find($main_id);

        if (!$main_user) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found');
        }
        return $main_user;
    }

    private function findUsers(BaseUser $main_user, $element_ids)
    {
        $users = $this->entityManager->getRepository(BaseUser::class)->findBaseDoUnionEntities(
            $main_user,
            $element_ids
        );

        if (count($users) !== count($element_ids) - 1) {
            throw Exception::create(Exception::ENTITY_NOT_FOUND, 'exception.entity_not_found');
        }
        return $users;
    }

    protected function batchUnion($element_ids)
    {
        return $this->render(
            'Admin/BaseUser/batchUnion.html.twig',
            $this->baseUnion('BaseUser', $element_ids)
        );
    }
}
