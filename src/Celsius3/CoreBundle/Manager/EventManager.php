<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

namespace Celsius3\CoreBundle\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Celsius3\CoreBundle\Entity\Event\MultiInstanceRequest;
use Celsius3\CoreBundle\Entity\Event\SingleInstanceReceiveEvent;
use Celsius3\CoreBundle\Entity\Event\MultiInstanceReceiveEvent;
use Celsius3\CoreBundle\Entity\Institution;
use Celsius3\CoreBundle\Entity\Request;
use Celsius3\CoreBundle\Exception\NotFoundException;
use Celsius3\CoreBundle\Entity\Instance;

class EventManager
{
    const EVENT__CREATION = 'creation';
    const EVENT__SEARCH = 'search';
    const EVENT__SINGLE_INSTANCE_REQUEST = 'sirequest';
    const EVENT__MULTI_INSTANCE_REQUEST = 'mirequest';
    const EVENT__APPROVE = 'approve';
    const EVENT__RECLAIM = 'reclaim';
    const EVENT__SINGLE_INSTANCE_RECEIVE = 'sireceive';
    const EVENT__MULTI_INSTANCE_RECEIVE = 'mireceive';
    const EVENT__DELIVER = 'deliver';
    const EVENT__CANCEL = 'cancel';
    const EVENT__LOCAL_CANCEL = 'lcancel';
    const EVENT__REMOTE_CANCEL = 'rcancel';
    const EVENT__ANNUL = 'annul';
    const EVENT__TAKE = 'take';
    const EVENT__UPLOAD = 'upload';
    const EVENT__REUPLOAD = 'reupload';
    const EVENT__SEARCH_PENDINGS = 'searchpendings';
    const EVENT__NO_SEARCH_PENDINGS = 'nosearchpendings';
    // Fake events
    const EVENT__REQUEST = 'request';
    const EVENT__RECEIVE = 'receive';
    private $class_prefix = 'Celsius3\\CoreBundle\\Entity\\Event\\';
    public $event_classes = array(
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
    );
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

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

    public function getClassNameForEvent($event)
    {
        if (!array_key_exists($event, $this->event_classes)) {
            throw $this->createNotFoundException('Event not found.');
        }

        return $this->event_classes[$event];
    }

    public function getFullClassNameForEvent($event)
    {
        if (!array_key_exists($event, $this->event_classes)) {
            throw $this->createNotFoundException('Event not found.');
        }

        return $this->class_prefix . $this->event_classes[$event];
    }

    private function prepareExtraDataForSearch(Request $request, array $extraData, Instance $instance)
    {
        $httpRequest = $this->container->get('request_stack')->getCurrentRequest();

        $extraData['result'] = $httpRequest->request->get('result', null);

        if ($httpRequest->request->has('catalog_id')) {
            $em = $this->container->get('doctrine.orm.entity_manager');
            $extraData['catalog'] = $em->getRepository('Celsius3CoreBundle:Catalog')
                    ->find($httpRequest->request->get('catalog_id'));
        } else {
            $this->container->get('session')->getFlashBag()->add('error', 'There was an error changing the state.');

            throw new NotFoundHttpException();
        }

        return $extraData;
    }

    private function prepareExtraDataForRequest(Request $request, array $extraData, Instance $instance)
    {
        $httpRequest = $this->container->get('request_stack')->getCurrentRequest();

        $extraData['observations'] = $httpRequest->request->get('observations', null);

        $em = $this->container->get('doctrine.orm.entity_manager');
        if ($httpRequest->request->get('provider') === 'web') {
            $provider = $em->getRepository('Celsius3CoreBundle:Web')
                    ->findOneBy(array());
        } else if ($httpRequest->request->get('provider') === 'author') {
            $provider = $em->getRepository('Celsius3CoreBundle:Author')
                    ->findOneBy(array());
        } else {
            $provider = $em->getRepository('Celsius3CoreBundle:Institution')
                ->find($httpRequest->request->get('provider'));
        }

        if ($provider) {
            $extraData['provider'] = $provider;
        } else {
            $this->container->get('session')->getFlashBag()->add('error', 'There was an error changing the state.');

            throw new NotFoundHttpException();
        }

        return $extraData;
    }

    private function prepareExtraDataForReceive(Request $request, array $extraData, Instance $instance)
    {
        $httpRequest = $this->container->get('request_stack')->getCurrentRequest();

        if (!$httpRequest->request->has('request')) {
            $this->container->get('session')->getFlashBag()->add('error', 'There was an error changing the state.');

            throw new NotFoundHttpException();
        }

        $extraData['observations'] = $httpRequest->request->get('observations', null);
        $extraData['request'] = $this->container
                ->get('doctrine.orm.entity_manager')
                ->getRepository('Celsius3CoreBundle:Event\\Event')
                ->find($httpRequest->request->get('request'));
        $extraData['files'] = $httpRequest->files->all();

        return $extraData;
    }

    private function prepareExtraDataForUpload(Request $request, array $extraData, Instance $instance)
    {
        $httpRequest = $this->container->get('request_stack')->getCurrentRequest();

        $extraData['files'] = $httpRequest->files->all();

        return $extraData;
    }

    private function prepareExtraDataForReupload(Request $request, array $extraData, Instance $instance)
    {
        $httpRequest = $this->container->get('request_stack')->getCurrentRequest();

        if (!$httpRequest->request->has('receive')) {
            $this->container->get('session')->getFlashBag()->add('error', 'There was an error changing the state.');

            throw new NotFoundHttpException();
        }

        $extraData['observations'] = $httpRequest->request->get('observations', null);
        $extraData['receive'] = $this->container
                ->get('doctrine.orm.entity_manager')
                ->getRepository('Celsius3CoreBundle:Event\\Event')
                ->find($httpRequest->request->get('receive'));
        $extraData['files'] = $httpRequest->files->all();

        return $extraData;
    }

    private function prepareExtraDataForApprove(Request $request, array $extraData, Instance $instance)
    {
        $httpRequest = $this->container->get('request_stack')->getCurrentRequest();
        $em = $this->container->get('doctrine.orm.entity_manager');
        if (!$httpRequest->request->has('receive')) {
            $this->container->get('session')->getFlashBag()
                    ->add('error', 'There was an error changing the state.');

            throw new NotFoundHttpException();
        }

        $extraData['receive'] = $em->getRepository('Celsius3CoreBundle:Event\\Event')
                ->find($httpRequest->request->get('receive'));

        if (!$extraData['receive']) {
            throw new NotFoundHttpException();
        }

        return $extraData;
    }

    private function prepareExtraDataForReclaim(Request $request, array $extraData, Instance $instance)
    {
        $httpRequest = $this->container->get('request_stack')->getCurrentRequest();
        $em = $this->container->get('doctrine.orm.entity_manager');
        if (!$httpRequest->request->has('request') && !$httpRequest->request->has('receive')) {
            $this->container->get('session')->getFlashBag()
                    ->add('error', 'There was an error changing the state.');

            throw new NotFoundHttpException();
        }

        if ($httpRequest->request->has('request')) {
            $key = 'request';
            $id = $httpRequest->request->get('request');
        } else {
            $key = 'receive';
            $id = $httpRequest->request->get('receive');
        }

        $event = $em->getRepository('Celsius3CoreBundle:Event\\Event')
                ->find($id);

        if (!$event) {
            $this->container->get('session')->getFlashBag()
                    ->add('error', 'There was an error changing the state.');

            throw new NotFoundHttpException();
        }

        $extraData[$key] = $event;

        if (!$httpRequest->request->has('observations') || $httpRequest->request->get('observations') === '') {
            $this->container->get('session')->getFlashBag()
                    ->add('error', 'There was an error changing the state.');

            throw new NotFoundHttpException();
        }

        $extraData['observations'] = $httpRequest->request->get('observations');

        return $extraData;
    }

    private function prepareExtraDataForCancel(Request $request, array $extraData, Instance $instance)
    {
        $httpRequest = $this->container->get('request_stack')->getCurrentRequest();
        $em = $this->container->get('doctrine.orm.entity_manager');

        if ($httpRequest->request->has('request')) {
            $extraData['request'] = $em->getRepository('Celsius3CoreBundle:Event\\Event')
                    ->find($httpRequest->request->get('request'));

            $httpRequest->query->remove('request');
            if (!$extraData['request']) {
                throw new NotFoundHttpException();
            }
        } else {
            $extraData['httprequest'] = $httpRequest;
            if ($request->getInstance()->getId() != $instance->getId()) {
                $extraData['remoterequest'] = $request->getOrder()
                        ->getRequest($instance)
                        ->getState(StateManager::STATE__CREATED)
                        ->getRemoteEvent();
            }
            $extraData['sirequests'] = $em->getRepository('Celsius3CoreBundle:Event\\SingleInstanceRequestEvent')
                    ->findBy(array(
                'request' => $request->getId(),
                'isCancelled' => false,
                'instance' => $instance->getId(),
            ));
            $extraData['mirequests'] = $em->getRepository('Celsius3CoreBundle:Event\\MultiInstanceRequestEvent')
                    ->findBy(array(
                'request' => $request->getId(),
                'isCancelled' => false,
                'instance' => $instance->getId(),
            ));
        }

        if (!$httpRequest->request->has('observations') || $httpRequest->request->get('observations') === '') {
            $this->container->get('session')->getFlashBag()
                    ->add('error', 'There was an error changing the state.');

            throw new NotFoundHttpException();
        }

        $extraData['observations'] = $httpRequest->request->get('observations');

        return $extraData;
    }

    private function prepareExtraDataForAnnul(Request $request, array $extraData, Instance $instance)
    {
        if ($request->getInstance()->getId() !== $instance->getId()) {
            $extraData['request'] = $request
                    ->getState(StateManager::STATE__CREATED, $instance)
                    ->getRemoteEvent();
        }

        return $extraData;
    }

    public function getRealEventName($event, array $extraData)
    {
        switch ($event) {
            case self::EVENT__REQUEST:
                $event = ($extraData['provider'] instanceof Institution && $extraData['provider']->getCelsiusInstance()) ? self::EVENT__MULTI_INSTANCE_REQUEST : self::EVENT__SINGLE_INSTANCE_REQUEST;
                break;
            case self::EVENT__RECEIVE:
                $event = $extraData['request']->getRequest()->getPreviousRequest() ? self::EVENT__MULTI_INSTANCE_RECEIVE : self::EVENT__SINGLE_INSTANCE_RECEIVE;
                break;
            case self::EVENT__CANCEL:
                $event = array_key_exists('request', $extraData) ? (($extraData['request'] instanceof MultiInstanceRequest) ? self::EVENT__REMOTE_CANCEL : self::EVENT__LOCAL_CANCEL) : self::EVENT__CANCEL;
                break;
            default:
                ;
        }

        return $event;
    }

    public function prepareExtraData($event, Request $request, Instance $instance)
    {
        $methodName = 'prepareExtraDataFor' . ucfirst($event);

        return $this->$methodName($request, array(), $instance);
    }

    public function cancelRequests(array $requests, HttpRequest $httpRequest)
    {
        foreach ($requests as $request) {
            $receptions = array_filter($this->getEvents(self::EVENT__RECEIVE, $request->getRequest()->getId()), function ($reception) use ($request) {
                /**
                 * @todo Probar esto mas exhaustivamente.
                 */
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
        $em = $this->container->get('doctrine.orm.entity_manager');

        if ($event === self::EVENT__REQUEST) {
            $repositories = array(
                $this->event_classes[self::EVENT__MULTI_INSTANCE_REQUEST],
                $this->event_classes[self::EVENT__SINGLE_INSTANCE_REQUEST],
            );
        } elseif ($event === self::EVENT__RECEIVE) {
            $repositories = array(
                $this->event_classes[self::EVENT__MULTI_INSTANCE_RECEIVE],
                $this->event_classes[self::EVENT__SINGLE_INSTANCE_RECEIVE],
                $this->event_classes[self::EVENT__UPLOAD],
            );
        } else {
            $repositories = array(
                $this->event_classes[$event],
            );
        }

        $results = array();

        foreach ($repositories as $repository) {
            $results = array_merge($results, $em->getRepository('Celsius3CoreBundle:Event\\' . $repository)
                            ->findBy(array('request' => $request_id)));
        }

        return $results;
    }
}
