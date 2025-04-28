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

namespace Celsius3\Controller\Base;

use Celsius3\Controller\Core\EntityController;
use Celsius3\Entity\Event\Event;
use Celsius3\Entity\Event\MultiInstanceEvent;
use Celsius3\Entity\Event\ReclaimEvent;
use Celsius3\Entity\Request;

use Celsius3\Controller\Core\HtmlRenderer;
use Celsius3\Controller\Core\RestRenderer;
use Celsius3\Exception\Exception;
use Celsius3\Helper\ConfigurationHelper;
use Celsius3\Manager\InstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Celsius3\Helper\InstanceHelper;
use Celsius3\Helper\LifecycleHelper;
use Celsius3\Manager\EventManager;
use Celsius3\Manager\FilterManager;
use Celsius3\Manager\UnionManager;
use Celsius3\Manager\UserManager;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EventController extends EntityController
{

    protected $reqRepository;
    protected $eventRepository;
    protected $recEventRepository;
    protected $miEventRepository;

    public function __construct(
        protected EventManager $eventManager,
        protected LifecycleHelper $lifecycleHelper,
        ValidatorInterface $validator,
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
        $this->setEntity(Event::class);

        $this->reqRepository = $this->entityManager->getRepository(Request::class);
        $this->eventRepository = $this->entityManager->getRepository(Event::class);
        $this->recEventRepository = $this->entityManager->getRepository(ReclaimEvent::class);
        $this->miEventRepository = $this->entityManager->getRepository(MultiInstanceEvent::class);

        $this->setSortDefaults([
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        ]);

        $this->htmlRenderer->setTemplatePrefixFromObj($this);

        parent::initialize();
    }


    protected function getAllEvents(string $request_id): array
    {
        $events = $this->getEvents($request_id);
        $remote_events = $this->getRemoteEvents($request_id);

        $all = array_merge($events, $remote_events);

        return $this->addReclaimEvents($all);
    }


    protected function getEvents(string $request_id): array
    {
        $events = $this->eventRepository
            ->findBy(['request' => $request_id]);
        return $events;
    }


    protected function getRemoteEvents(string $request_id): array
    {
        $requests = $this->reqRepository
            ->findBy(['previousRequest' => $request_id]);
        $requests_ids = array_map(
            fn (Request $item): int => $item->getId(),
            $requests
        );
        $remote_events = $this->miEventRepository
            ->getRemoteEvents($requests_ids);
        return $remote_events;
    }


    private function addReclaimEvents(array $all): array
    {
        $keys = array_map(
            fn (Event $e): array => $e->getId(),
            $all
        );

        $reclaim_events = $this->recEventRepository
            ->getReclaimEventsFor($keys);

        foreach ($reclaim_events as $e) {
            if (!in_array($e->getId(), $keys)) {
                $all[] = $e;
            }
        }
        return $all;
    }


    protected function findRequest(string $request_id): Request
    {
        $request = $this->reqRepository->find($request_id);

        if (!$request) $this->error(Exception::ENTITY_NOT_FOUND, entity: Request::class);

        if (!$request->getOperator()) {
            $request->setOperator($this->getUser());
        }

        return $request;
    }


    protected function findEvent(string $event_id): Event
    {
        $request = $this->eventRepository->find($event_id);

        if (!$request) $this->error(Exception::ENTITY_NOT_FOUND, entity: Event::class);

        return $request;
    }
}