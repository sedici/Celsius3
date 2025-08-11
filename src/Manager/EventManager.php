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

namespace Celsius3\Manager;

use Celsius3\Entity\Author;
use Celsius3\Entity\Catalog;
use Celsius3\Entity\Event\AnnulEvent;
use Celsius3\Entity\Event\ApproveEvent;
use Celsius3\Entity\Event\CancelEvent;
use Celsius3\Entity\Event\CreationEvent;
use Celsius3\Entity\Event\DeliverEvent;
use Celsius3\Entity\Event\Event;
use Celsius3\Entity\Event\LocalCancelEvent;
use Celsius3\Entity\Event\SingleInstanceRequestEvent;
use Celsius3\Entity\Web;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Celsius3\Entity\Event\MultiInstanceRequestEvent;
use Celsius3\Entity\Event\SingleInstanceReceiveEvent;
use Celsius3\Entity\Event\MultiInstanceReceiveEvent;
use Celsius3\Entity\Event\NoSearchPendingsEvent;
use Celsius3\Entity\Event\ReclaimEvent;
use Celsius3\Entity\Event\RemoteCancelEvent;
use Celsius3\Entity\Event\ReuploadEvent;
use Celsius3\Entity\Event\SearchEvent;
use Celsius3\Entity\Event\SearchPendingsEvent;
use Celsius3\Entity\Event\TakeEvent;
use Celsius3\Entity\Event\UploadEvent;
use Celsius3\Entity\Institution;
use Celsius3\Entity\Request;
use Celsius3\Exception\Exception;
use Celsius3\Exception\NotFoundException;
use Celsius3\Entity\Instance;
use Celsius3\Helper\LifecycleHelper;
use Dom\Entity;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EventManager
{
    public const EVENT__CREATION = 'creation';
    public const EVENT__SEARCH = 'search';
    public const EVENT__SINGLE_INSTANCE_REQUEST = 'sirequest';
    public const EVENT__MULTI_INSTANCE_REQUEST = 'mirequest';
    public const EVENT__APPROVE = 'approve';
    public const EVENT__RECLAIM = 'reclaim';
    public const EVENT__SINGLE_INSTANCE_RECEIVE = 'sireceive';
    public const EVENT__MULTI_INSTANCE_RECEIVE = 'mireceive';
    public const EVENT__DELIVER = 'deliver';
    public const EVENT__CANCEL = 'cancel';
    public const EVENT__LOCAL_CANCEL = 'lcancel';
    public const EVENT__REMOTE_CANCEL = 'rcancel';
    public const EVENT__ANNUL = 'annul';
    public const EVENT__TAKE = 'take';
    public const EVENT__UPLOAD = 'upload';
    public const EVENT__REUPLOAD = 'reupload';
    public const EVENT__SEARCH_PENDINGS = 'searchpendings';
    public const EVENT__NO_SEARCH_PENDINGS = 'nosearchpendings';
    // Fake events
    public const EVENT__REQUEST = 'request';
    public const EVENT__RECEIVE = 'receive';

    private $class_prefix = 'Celsius3\\Entity\\Event\\';
    public $event_classes = [
        self::EVENT__CREATION                   => CreationEvent::class,
        self::EVENT__SEARCH                     => SearchEvent::class,
        self::EVENT__SINGLE_INSTANCE_REQUEST    => SingleInstanceRequestEvent::class,
        self::EVENT__MULTI_INSTANCE_REQUEST     => MultiInstanceRequestEvent::class,
        self::EVENT__APPROVE                    => ApproveEvent::class,
        self::EVENT__RECLAIM                    => ReclaimEvent::class,
        self::EVENT__MULTI_INSTANCE_RECEIVE     => MultiInstanceReceiveEvent::class,
        self::EVENT__SINGLE_INSTANCE_RECEIVE    => SingleInstanceReceiveEvent::class,
        self::EVENT__DELIVER                    => DeliverEvent::class,
        self::EVENT__CANCEL                     => CancelEvent::class,
        self::EVENT__LOCAL_CANCEL               => LocalCancelEvent::class,
        self::EVENT__REMOTE_CANCEL              => RemoteCancelEvent::class,
        self::EVENT__ANNUL                      => AnnulEvent::class,
        self::EVENT__TAKE                       => TakeEvent::class,
        self::EVENT__UPLOAD                     => UploadEvent::class,
        self::EVENT__REUPLOAD                   => ReuploadEvent::class,
        self::EVENT__SEARCH_PENDINGS            => SearchPendingsEvent::class,
        self::EVENT__NO_SEARCH_PENDINGS         => NoSearchPendingsEvent::class,
    ];

    protected ?LifecycleHelper $lifecycleHelper = null;

    public function __construct(
        protected ContainerInterface $container,
        protected EntityManagerInterface $entityManager,
        protected RequestStack $requestStack,
        protected FlashBagInterface $flashBag
    ) { }

    public function getLifecycleHelper(): LifecycleHelper
    {
        if ($this->lifecycleHelper === null) {
            $this->lifecycleHelper = $this->container->get(LifecycleHelper::class);
        }
        return $this->lifecycleHelper;
    }

    public function setLifecycleHelper(LifecycleHelper $lifecycleHelper): void
    {
        $this->lifecycleHelper = $lifecycleHelper;
    }

    public function __call($name, $arguments)
    {
        if (str_starts_with((string) $name, 'prepareExtraDataFor')) {
            $data = [];
            if (method_exists($this, $name)) {
                $data = call_user_func_array($this->$name, $arguments);
            }

            return $data;
        }
    }

    public function createNotFoundException($message = 'Not Found', ?\Exception $previous = null): NotFoundException
    {
        return new NotFoundException($message, $previous);
    }

    public function getClassNameForEvent($event): string
    {
        if (!array_key_exists($event, $this->event_classes)) {
            throw Exception::create(Exception::NOT_FOUND, 'exception.not_found.event');
        }

        return $this->event_classes[$event];
    }

    public function getFullClassNameForEvent($event): string
    {
        if (!array_key_exists($event, $this->event_classes)) {
            throw Exception::create(Exception::NOT_FOUND, 'exception.not_found.event');
        }

        return $this->event_classes[$event];
    }

    public function prepareExtraDataForSearch(): array
    {
        $httpReqData = $this->requestStack->getCurrentRequest()->toArray();

        $extra_data = [];
        $extra_data['result'] = $httpReqData['result'] ?? null;

        if (!$httpReqData['catalog_id']) {
            $this->flashBag->add('error', 'There was an error changing the state.');

            throw Exception::create(Exception::NOT_FOUND);
        }

        $entity_manager = $this->entityManager;
        $extra_data['catalog'] = $entity_manager->getRepository(Catalog::class)
            ->find($httpReqData['catalog_id']);

        return $extra_data;
    }

    public function prepareExtraDataForRequest(): array
    {
        $httpReqData = $this->requestStack->getCurrentRequest()->toArray();

        $extra_data = [];
        $extra_data['observations'] = $httpReqData['observations'] ?? null;

        $entity_manager = $this->entityManager;
        if ($httpReqData['provider'] === 'web') {
            $provider = $entity_manager->getRepository(Web::class)
                ->findOneBy([]);
        } elseif ($httpReqData['provider'] === 'author') {
            $provider = $entity_manager->getRepository(Author::class)
                ->findOneBy([]);
        } else {
            $provider = $entity_manager->getRepository(Institution::class)
                ->find($httpReqData['provider']['id']);
        }

        if ($provider) {
            $extra_data['provider'] = $provider;
        } else {
            $this->flashBag->add('error', 'There was an error changing the state.');

            throw Exception::create(Exception::NOT_FOUND);
        }

        return $extra_data;
    }

    public function prepareExtraDataForReceive(Request $request): array
    {
        $httpReq = $this->requestStack->getCurrentRequest();

        $params = $httpReq->request->all();

        $requestId = $params['request'];
        if (!is_numeric($requestId)) {
            $this->flashBag->add('error', 'There was an error changing the state.');
            throw Exception::create(Exception::NOT_FOUND);
        }

        $extraData = [];
        $extraData['observations'] = $params['observations'] ?? null;
        $extraData['delivery_type'] = $params['delivery_type'] ?? (
            $request->getOwner()->getPdf() ? 'pdf' : 'printed'
        );

        $extraData['request'] = $this->entityManager
            ->getRepository(Event::class)
            ->find($params['request']);

        $extraData['files'] = $httpReq->files->all();

        return $extraData;
    }

    private function prepareExtraDataForUpload(): array
    {
        $httpRequest = $this->requestStack->getCurrentRequest();

        $extraData = [];
        $extraData['files'] = $httpRequest->files->all();

        return $extraData;
    }

    private function prepareExtraDataForReupload(): array
    {
        $httpRequest = $this->requestStack->getCurrentRequest();
        $httpReqData = $httpRequest->toArray();

        if (!isset($httpReqData['receive'])) {
            $this->flashBag->add('error', 'There was an error changing the state.');

            throw Exception::create(Exception::NOT_FOUND);
        }

        $extraData = [];
        $extraData['observations'] = $httpReqData['observations'] ?? null;
        $extraData['receive'] = $this->entityManager
                ->getRepository(Event::class)
                ->find($httpReqData['receive']);
        $extraData['files'] = $httpRequest->files->all();

        return $extraData;
    }

    public function prepareExtraDataForApprove(): array
    {
        $httpRequest = $this->requestStack->getCurrentRequest();
        $httpReqData = $httpRequest->toArray();

        $em = $this->entityManager;
        if (!isset($httpReqData['receive'])) {
            $this->flashBag
                ->add('error', 'There was an error changing the state.');

            throw Exception::create(Exception::NOT_FOUND);
        }

        $extraData = [];
        $extraData['receive'] = $em->getRepository(Event::class)
            ->find($httpReqData['receive']);

        if (!$extraData['receive']) {
            throw Exception::create(Exception::NOT_FOUND);
        }

        return $extraData;
    }

    public function prepareExtraDataForReclaim(): array
    {
        $httpRequest = $this->requestStack->getCurrentRequest();
        $httpReqData = $httpRequest->toArray();

        $em = $this->entityManager;
        if (!$httpReqData['request'] && !$httpReqData['receive']) {
            $this->flashBag
                ->add('error', 'There was an error changing the state.');

            throw Exception::create(Exception::NOT_FOUND);
        }

        if ($httpReqData['request']) {
            $key = 'request';
            $id = $httpReqData['request'];
        } else {
            $key = 'receive';
            $id = $httpReqData['receive'];
        }

        $event = $em->getRepository(Event::class)
            ->find($id);

        if (!$event) {
            $this->flashBag
                ->add('error', 'There was an error changing the state.');

            throw Exception::create(Exception::NOT_FOUND);
        }

        $extraData = [];
        $extraData[$key] = $event;

        if (!isset($httpReqData['observations']) || $httpReqData['observations'] === '') {
            $this->flashBag
                ->add('error', 'There was an error changing the state.');

            throw Exception::create(Exception::NOT_FOUND);
        }

        $extraData['observations'] = $httpReqData['obserations'];

        return $extraData;
    }

    public function prepareExtraDataForCancel(Request $request, Instance $instance): array
    {
        $httpRequest = $this->requestStack->getCurrentRequest();
        $httpReqData = $httpRequest->toArray();

        $em = $this->entityManager;
        $extraData = [];

        if (isset($httpReqData['request'])) {
            $extraData['request'] = $em->getRepository(Event::class)
                    ->find($httpReqData['request']);

            unset($httpReqData['request']);
            if (!$extraData['request']) {
                throw Exception::create(Exception::NOT_FOUND);
            }
        } else {
            $extraData['httprequest'] = $httpRequest;
            if ($request->getInstance()->getId() !== $instance->getId()) {
                $extraData['remoterequest'] = $request->getOrder()
                    ->getRequest($instance)
                    ->getState(StateManager::STATE__CREATED)
                    ->getRemoteEvent();
            }
            $extraData['sirequests'] = $em->getRepository(SingleInstanceRequestEvent::class)
                    ->findBy([
                'request' => $request->getId(),
                'cancelled' => false,
                'instance' => $instance->getId(),
            ]);
            $extraData['mirequests'] = $em->getRepository(MultiInstanceRequestEvent::class)
                    ->findBy([
                'request' => $request->getId(),
                'cancelled' => false,
                'instance' => $instance->getId(),
            ]);
        }

        if (!isset($httpReqData['observations']) || $httpReqData['observations'] === '') {
            $this->flashBag
                ->add('error', 'There was an error changing the state.');

            throw Exception::create(Exception::NOT_FOUND);
        }

        $extraData['cancelled_by_user'] = $httpReqData['cancelled_by_user'] ?? false;

        $extraData['observations'] = $httpReqData['observations'];

        return $extraData;
    }


    public function prepareExtraDataForAnnul(Request $request, Instance $instance): array
    {
        $extraData = [];

        if ($request->getInstance()->getId() !== $instance->getId() || $request->getPreviousRequest() !== null) {
            $extraData['request'] = $request
                ->getState(StateManager::STATE__CREATED)
                ->getRemoteEvent();
        }

        return $extraData;
    }


    public function getRealEventName(
        ?string $event = null,
        ?array $extraData = null,
        ?Instance $instance = null,
        ?Request $request = null
    ): ?string {
        switch ($event) {
            case self::EVENT__REQUEST:
                $event = (
                    $extraData['provider'] instanceof Institution
                    && $extraData['provider']->findCelsiusInstance()
                    && !$request->getOrder()->hasRequest($extraData['provider']->findCelsiusInstance())
                    && $extraData['provider']->findCelsiusInstance()->getId() !== $instance->getId()
                    && $request->getPreviousRequest() == null
                )
                    ? self::EVENT__MULTI_INSTANCE_REQUEST
                    : self::EVENT__SINGLE_INSTANCE_REQUEST;
                break;
            case self::EVENT__RECEIVE:
                $event = $extraData['request']->getRequest()->getPreviousRequest()
                    ? self::EVENT__MULTI_INSTANCE_RECEIVE
                    : self::EVENT__SINGLE_INSTANCE_RECEIVE;
                break;
            case self::EVENT__CANCEL:
                $event = array_key_exists('request', $extraData)
                    ? (($extraData['request'] instanceof MultiInstanceRequestEvent)
                        ? self::EVENT__REMOTE_CANCEL
                        : self::EVENT__LOCAL_CANCEL)
                    : self::EVENT__CANCEL;
                break;
            default:
        }

        return $event;
    }

    public function getRealRequestEventName(array $extraData, Instance $instance, Request $request): string
    {
        return (
            $extraData['provider'] instanceof Institution && $extraData['provider']->findCelsiusInstance()
            && !$request->getOrder()->hasRequest($extraData['provider']->findCelsiusInstance())
            && $extraData['provider']->findCelsiusInstance()->getId() !== $instance->getId()
            && $request->getPreviousRequest() === null
        ) ? self::EVENT__MULTI_INSTANCE_REQUEST : self::EVENT__SINGLE_INSTANCE_REQUEST;
    }

    public function getRealReceiveEventName(array $extraData, Instance $instance, Request $request): string
    {
        return $extraData['request']->getRequest()->getPreviousRequest()
            ? self::EVENT__MULTI_INSTANCE_RECEIVE : self::EVENT__SINGLE_INSTANCE_RECEIVE;
    }

    public function getRealCancelEventName(array $extraData): string
    {
        if (!isset($extraData['request'])) {
            return self::EVENT__CANCEL;
        }

        return $extraData['request'] instanceof MultiInstanceRequestEvent ?
                self::EVENT__REMOTE_CANCEL : self::EVENT__LOCAL_CANCEL;
    }

    public function prepareExtraData($event, Request $request, Instance $instance): ?array
    {
        return match ($event) {
            self::EVENT__SEARCH => $this->prepareExtraDataForSearch(),
            self::EVENT__REQUEST => $this->prepareExtraDataForRequest(),
            self::EVENT__RECEIVE => $this->prepareExtraDataForReceive($request),
            self::EVENT__DELIVER => $this->prepareExtraDataForDeliver(),
            self::EVENT__UPLOAD => $this->prepareExtraDataForUpload(),
            self::EVENT__REUPLOAD => $this->prepareExtraDataForReupload(),
            self::EVENT__APPROVE => $this->prepareExtraDataForApprove(),
            self::EVENT__RECLAIM => $this->prepareExtraDataForReclaim(),
            self::EVENT__CANCEL => $this->prepareExtraDataForCancel($request, $instance),
            self::EVENT__ANNUL => $this->prepareExtraDataForAnnul($request, $instance),
            default => null,
        };
    }

    public function cancelRequests(array $requests, HttpRequest $httpRequest): void
    {
        foreach ($requests as $request) {
            $receptions = array_filter(
                $this->getEvents(
                    self::EVENT__RECEIVE,
                    $request->getRequest()->getId()
                ),
                function ($reception) use ($request): bool {
                    if ($reception instanceof SingleInstanceReceiveEvent || $reception instanceof MultiInstanceReceiveEvent) {
                        return $reception->getRequestEvent()->getId() === $request->getId();
                    } else {
                        return $reception->getRequest()->getInstance()->getId() === $request->getRemoteInstance()->getId();
                    }
                }
            );
            if (count($receptions) === 0) {
                $httpRequest->request->set('request', $request->getId());
                // throw new \Exception('asdasdas' . (string) $httpRequest->request->get('request'));
                $this->getLifecycleHelper()->createEvent(self::EVENT__CANCEL, $request->getRequest());
                $httpRequest->request->remove('request');
            }
        }
    }

    public function cancelSearches($searches): void
    {
        $em = $this->entityManager;
        foreach ($searches as $search) {
            $search->setResult(CatalogManager::CATALOG__NON_SEARCHED);
            $em->persist($search);
        }
        $em->flush();
    }

    public function getEvents($event, $request_id): array
    {
        /** @var EntityManagerInterface $entity_manager */
        $entity_manager = $this->entityManager;

        if ($event === self::EVENT__REQUEST) {
            $entities_classes = [
                $this->event_classes[self::EVENT__MULTI_INSTANCE_REQUEST],
                $this->event_classes[self::EVENT__SINGLE_INSTANCE_REQUEST],
            ];
        } elseif ($event === self::EVENT__RECEIVE) {
            $entities_classes = [
                $this->event_classes[self::EVENT__MULTI_INSTANCE_RECEIVE],
                $this->event_classes[self::EVENT__SINGLE_INSTANCE_RECEIVE],
                $this->event_classes[self::EVENT__UPLOAD],
            ];
        } else {
            $entities_classes = [
                $this->event_classes[$event],
            ];
        }

        $results = [];
        foreach ($entities_classes as $entity_class) {
            $results[] = $entity_manager->getRepository($entity_class)
                ->findBy(['request' => $request_id]);
        }

        return array_merge(...$results);
    }
}
