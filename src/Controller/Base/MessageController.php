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
use Celsius3\Entity\Message;
use Celsius3\Entity\Mixin\ProviderTrait;

use Celsius3\Controller\Core\HtmlRenderer;
use Celsius3\Controller\Core\RestRenderer;
use Celsius3\Entity\BaseUser;
use Celsius3\Entity\Thread;
use Celsius3\Exception\Exception;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class MessageController extends EntityController
{

    use ProviderTrait;

    protected EntityRepository $threadRepository;


    public function initialize(): void
    {
        $this->setEntity(Message::class);

        parent::initialize();

        $this->htmlRenderer->setTemplatePrefix('bundles/FOSMessageBundle/Message/');
        $this->setInstanceDependent(true);
        $this->setSortDefaults([ 'wrap-queries' => false ]);

        $this->threadRepository = $this->entityManager->getRepository(Thread::class);
    }


    public function listQuery(
        ?bool $isInstanceDependent = null
    ): QueryBuilder {
        // throw new \Exception((string)var_dump($this->threadRepository));
        return $this->threadRepository->getParticipantInboxThreadsQueryBuilder(
            $this->entityManager->getRepository(BaseUser::class)->find(634)
        );
    }
}