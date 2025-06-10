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
use Celsius3\Entity\Event\Event;
use Celsius3\Entity\Event\SingleInstanceRequestEvent;
use Celsius3\Entity\Web;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Celsius3\Entity\Event\MultiInstanceRequestEvent;
use Celsius3\Entity\Event\SingleInstanceReceiveEvent;
use Celsius3\Entity\Event\MultiInstanceReceiveEvent;
use Celsius3\Entity\Institution;
use Celsius3\Entity\Request;
use Celsius3\Exception\Exception;
use Celsius3\Exception\NotFoundException;
use Celsius3\Entity\Instance;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

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
        self::EVENT__CREATION => 'CreationEvent',
        self::EVENT__SEARCH => 'SearchEvent',
        self::EVENT__SINGLE_INSTANCE_REQUEST => 'SingleInstanceRequestEvent',
        self::EVENT__MULTI_INSTANCE_REQUEST => 'MultiInstanceRequestEvent',
        self::EVENT__APPROVE => 'ApproveEvent',
        self::EVENT__RECLAIM => 'ReclaimEvent',
        self::EVENT__MULTI_INSTANCE_RECEIVE => 'MultiInstanceReceiveEvent',
        self::EVENT__SINGLE_INSTANCE_RECEIVE => 'SingleInstanceReceiveEvent',
        self::EVENT__DELIVER => 'DeliverEvent',
        self::EVENT__CANCEL => 'CancelEvent',
        self::EVENT__LOCAL_CANCEL => 'LocalCancelEvent',
        self::EVENT__REMOTE_CANCEL => 'RemoteCancelEvent',
        self::EVENT__ANNUL => 'AnnulEvent',
        self::EVENT__TAKE => 'TakeEvent',
        self::EVENT__UPLOAD => 'UploadEvent',
        self::EVENT__REUPLOAD => 'ReuploadEvent',
        self::EVENT__SEARCH_PENDINGS => 'SearchPendingsEvent',
        self::EVENT__NO_SEARCH_PENDINGS => 'NoSearchPendingsEvent',
    ];

    public function __construct(
        protected ContainerInterface $container,
        protected RequestStack $requestStack,
        protected EntityManagerInterface $entityManager,
        protected FlashBagInterface $flashBag
    ) { }

    public function __call($name, $arguments)
    {
        if (strpos($name, 'prepareExtraDataFor') === 0) {
            $data = array();
            if (method_exists($this, $name)) {
                $data = call_user_func_array($this->$name, $arguments);
            }

            return $data;
        }
    }

    public function createNotFoundException($message = 'Not Found', \Exception $previous = null)
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

        return $this->class_prefix.$this->event_classes[$event];
    }

    public function prepareExtraDataForSearch(): array
    {
        $params = $this->requestStack->getCurrentRequest()->toArray();

        $extra_data = [];

        $extra_data['result'] = (array_key_exists('result', $params)) ? $params['result'] : null;

        if (!array_key_exists('catalog_id', $params)) {
            $this->flashBag->add('error', 'Catalog ID is required for search events.');

            throw Exception::create(Exception::NOT_FOUND);
        }

        $extra_data['catalog'] = $this->entityManager->getRepository(Catalog::class)
            ->find($params['catalog_id']);

        return $extra_data;
    }

    public function prepareExtraDataForRequest(): array
    {
        $http_request = $this->container->get('request_stack')->getCurrentRequest();

        $extra_data = [];
        $extra_data['observations'] = $http_request->request->get('observations', null);

        $entity_manager = $this->container->get('doctrine.orm.entity_manager');
        if ($http_request->request->get('provider') === 'web') {
            $provider = $entity_manager->getRepository(Web::class)
                    ->findOneBy([]);
        } elseif ($http_request->request->get('provider') === 'author') {
            $provider = $entity_manager->getRepository(Author::class)
                    ->findOneBy([]);
        } else {
            $provider = $entity_manager->getRepository(Institution::class)
                    ->find($http_request->request->get('provider')['id']);
        }

        if ($provider) {
            $extra_data['provider'] = $provider;
        } else {
            $this->container->get('session')->getFlashBag()->add('error', 'There was an error changing the state.');

            throw Exception::create(Exception::NOT_FOUND);
        }

        return $extra_data;
    }

    public function prepareExtraDataForReceive(Request $request): array
    {
        $httpRequest = $this->container->get('request_stack')->getCurrentRequest();

        if (!$httpRequest->request->has('request')) {
            $this->container->get('session')->getFlashBag()->add('error', 'There was an error changing the state.');

            throw Exception::create(Exception::NOT_FOUND);
        }

        $extraData = array();
        $extraData['observations'] = $httpRequest->request->get('observations', null);
        $extraData['delivery_type'] = $httpRequest->request->get('delivery_type', $request->getOwner()->getPdf() ? 'pdf' : 'printed');
        $extraData['request'] = $this->container
                ->get('doctrine.orm.entity_manager')
                ->getRepository(Event::class)
                ->find($httpRequest->request->get('request'));
        $extraData['files'] = $httpRequest->files->all();

        return $extraData;
    }

    private function prepareExtraDataForUpload(): array
    {
        $httpRequest = $this->container->get('request_stack')->getCurrentRequest();

        $extraData = array();
        $extraData['files'] = $httpRequest->files->all();

        return $extraData;
    }

    private function prepareExtraDataForReupload(): array
    {
        $httpRequest = $this->container->get('request_stack')->getCurrentRequest();

        if (!$httpRequest->request->has('receive')) {
            $this->container->get('session')->getFlashBag()->add('error', 'There was an error changing the state.');

            throw Exception::create(Exception::NOT_FOUND);
        }

        $extraData = array();
        $extraData['observations'] = $httpRequest->request->get('observations', null);
        $extraData['receive'] = $this->container
                ->get('doctrine.orm.entity_manager')
                ->getRepository(Event::class)
                ->find($httpRequest->request->get('receive'));
        $extraData['files'] = $httpRequest->files->all();

        return $extraData;
    }

    public function prepareExtraDataForApprove(): array
    {
        $httpRequest = $this->container->get('request_stack')->getCurrentRequest();
        $em = $this->container->get('doctrine.orm.entity_manager');
        if (!$httpRequest->request->has('receive')) {
            $this->container->get('session')->getFlashBag()
                    ->add('error', 'There was an error changing the state.');

            throw Exception::create(Exception::NOT_FOUND);
        }

        $extraData = array();
        $extraData['receive'] = $em->getRepository(Event::class)
                ->find($httpRequest->request->get('receive'));

        if (!$extraData['receive']) {
            throw Exception::create(Exception::NOT_FOUND);
        }

        return $extraData;
    }

    public function prepareExtraDataForReclaim(): array
    {
        $httpRequest = $this->container->get('request_stack')->getCurrentRequest();
        $em = $this->container->get('doctrine.orm.entity_manager');
        if (!$httpRequest->request->has('request') && !$httpRequest->request->has('receive')) {
            $this->container->get('session')->getFlashBag()
                    ->add('error', 'There was an error changing the state.');

            throw Exception::create(Exception::NOT_FOUND);
        }

        if ($httpRequest->request->has('request')) {
            $key = 'request';
            $id = $httpRequest->request->get('request');
        } else {
            $key = 'receive';
            $id = $httpRequest->request->get('receive');
        }

        $event = $em->getRepository(Event::class)
                ->find($id);

        if (!$event) {
            $this->container->get('session')->getFlashBag()
                    ->add('error', 'There was an error changing the state.');

            throw Exception::create(Exception::NOT_FOUND);
        }

        $extraData = array();
        $extraData[$key] = $event;

        if (!$httpRequest->request->has('observations') || $httpRequest->request->get('observations') === '') {
            $this->container->get('session')->getFlashBag()
                    ->add('error', 'There was an error changing the state.');

            throw Exception::create(Exception::NOT_FOUND);
        }

        $extraData['observations'] = $httpRequest->request->get('observations');

        return $extraData;
    }

    public function prepareExtraDataForCancel(Request $request, Instance $instance): array
    {
        $params = $this->requestStack->getCurrentRequest()->toArray();
        $em = $this->entityManager;
        $extraData = [];

        if (array_key_exists('request', $params)) {
            $extraData['request'] = $em->getRepository(Event::class)
                    ->find($params['request']);

            unset($params['request']);
            if (!$extraData['request']) {
                throw Exception::create(Exception::NOT_FOUND);
            }
        } else {
            $extraData['httprequest'] = $params;
            if ($request->getInstance()->getId() !== $instance->getId()) {
                $extraData['remoterequest'] = $request->getOrder()
                        ->getRequest($instance)
                        ->getState(StateManager::STATE__CREATED)
                        ->getRemoteEvent();
            }
            $extraData['sirequests'] = $em->getRepository(SingleInstanceRequestEvent::class)
                ->findBy([
                    'request' => $request->getId(),
                    // 'cancelled' => false,
                    'instance' => $instance->getId(),
                ]
            );
            $extraData['mirequests'] = $em->getRepository(MultiInstanceRequestEvent::class)
                ->findBy([
                    'request' => $request->getId(),
                    // 'cancelled' => false,
                    'instance' => $instance->getId(),
                ]
            );
        }

        if (array_key_exists('observations', $params) || $params['observations'] === '') {
            $this->flashBag->add('error', 'There was an error changing the state.');

            throw Exception::create(Exception::NOT_FOUND);
        }

        $extraData['cancelled_by_user'] =
            (array_key_exists('cancelled_by_user', $params))
            ? $params['cancelled_by_user'] : false;

        $extraData['observations'] = $params['observations'];

        return $extraData;
    }

    public function prepareExtraDataForAnnul(Request $request, Instance $instance): array
    {
        $extraData = [];

        if ($request->getInstance()->getId() !== $instance->getId() || !is_null($request->getPreviousRequest())) {
            $extraData['request'] = $request
                ->getState(StateManager::STATE__CREATED, $instance)
                ->getRemoteEvent();
        }

        return $extraData;
    }

    public function getRealEventName(
        ?string $event = null,
        ?array $extraData = null,
        Instance $instance,
        Request $request
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

    public function getRealRequestEventName(array $extraData, Instance $instance, Request $request)
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

    public function cancelRequests(array $requests, HttpRequest $httpRequest)
    {
        foreach ($requests as $request) {
            $receptions = array_filter($this->getEvents(self::EVENT__RECEIVE, $request->getRequest()->getId()), function ($reception) use ($request) {
                if ($reception instanceof SingleInstanceReceiveEvent || $reception instanceof MultiInstanceReceiveEvent) {
                    return $reception->getRequestEvent()->getId() === $request->getId();
                } else {
                    return $reception->getRequest()->getInstance()->getId() === $request->getRemoteInstance()->getId();
                }
            });
            if (count($receptions) === 0) {
                $httpRequest->request->set('request', $request->getId());
                $this->container->get('celsius3_core.lifecycle_helper')->createEvent(self::EVENT__CANCEL, $request->getRequest());
                $httpRequest->request->remove('request');
            }
        }
    }

    public function cancelSearches($searches)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        foreach ($searches as $search) {
            $search->setResult(CatalogManager::CATALOG__NON_SEARCHED);
            $em->persist($search);
        }
        $em->flush();
    }

    public function getEvents($event, $request_id)
    {
        /** @var EntityManagerInterface $entity_manager */
        $entity_manager = $this->container->get('doctrine.orm.entity_manager');

        if ($event === self::EVENT__REQUEST) {
            $repositories = [
                $this->event_classes[self::EVENT__MULTI_INSTANCE_REQUEST],
                $this->event_classes[self::EVENT__SINGLE_INSTANCE_REQUEST],
            ];
        } elseif ($event === self::EVENT__RECEIVE) {
            $repositories = [
                $this->event_classes[self::EVENT__MULTI_INSTANCE_RECEIVE],
                $this->event_classes[self::EVENT__SINGLE_INSTANCE_RECEIVE],
                $this->event_classes[self::EVENT__UPLOAD],
            ];
        } else {
            $repositories = [
                $this->event_classes[$event],
            ];
        }

        $results = [];
        foreach ($repositories as $repository) {
            $results[] = $entity_manager
                ->getRepository('Celsius3\\Entity\\Event\\'.$repository)
                ->findBy(['request' => $request_id]);
        }

        return array_merge(...$results);
    }
}
