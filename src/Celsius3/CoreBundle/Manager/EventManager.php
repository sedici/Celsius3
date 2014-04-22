<?php

namespace Celsius3\CoreBundle\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Celsius3\CoreBundle\Document\Event\MultiInstanceRequest;
use Celsius3\CoreBundle\Document\Institution;
use Celsius3\CoreBundle\Document\Order;
use Celsius3\CoreBundle\Document\Request;
use Celsius3\CoreBundle\Exception\NotFoundException;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Form\Type\OrderReclaimType;

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
    // Fake events
    const EVENT__REQUEST = 'request';
    const EVENT__RECEIVE = 'receive';

    private $class_prefix = 'Celsius3\\CoreBundle\\Document\\Event\\';
    public $event_classes = array(
        self::EVENT__CREATION => 'CreationEvent',
        self::EVENT__SEARCH => 'SearchEvent',
        self::EVENT__SINGLE_INSTANCE_REQUEST => 'SingleInstanceRequestEvent',
        self::EVENT__MULTI_INSTANCE_REQUEST => 'MultiInstanceRequestEvent',
        self::EVENT__APPROVE => 'ApproveEvent',
        self::EVENT__RECLAIM => 'ReclaimEvent',
        self::EVENT__MULTI_INSTANCE_RECEIVE => 'MultiInstanceReceiveEvent',
        self::EVENT__SINGLE_INSTANCE_RECEIVE => 'SingleInstanceReceiveEvent',
        self::EVENT__DELIVER => 'Deliver', self::EVENT__CANCEL => 'CancelEvent',
        self::EVENT__LOCAL_CANCEL => 'LocalCancelEvent',
        self::EVENT__REMOTE_CANCEL => 'RemoteCancelEvent',
        self::EVENT__ANNUL => 'AnnulEvent',
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
            $dm = $this->container->get('doctrine.odm.mongodb.document_manager');
            $extraData['catalog'] = $dm->getRepository('Celsius3CoreBundle:Catalog')
                    ->find($httpRequest->request->get('catalog_id'));
        } else {
            $this->container->get('session')->getFlashBag()->add('error', 'There was an error changing the state.');

            throw new HttpException('There was an error changing the state.');
        }

        return $extraData;
    }

    private function prepareExtraDataForRequest(Request $request, array $extraData, Instance $instance)
    {
        $httpRequest = $this->container->get('request_stack')->getCurrentRequest();

        $extraData['observations'] = $httpRequest->request->get('observations', null);

        if ($httpRequest->request->has("provider")) {
            $dm = $this->container->get('doctrine.odm.mongodb.document_manager');
            $extraData['provider'] = $dm->getRepository('Celsius3CoreBundle:Institution')
                    ->find($httpRequest->request->get('provider'));
        } else {
            $this->container->get('session')->getFlashBag()->add('error', 'There was an error changing the state.');

            throw new HttpException('There was an error changing the state.');
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
                ->get('doctrine.odm.mongodb.document_manager')
                ->getRepository('Celsius3CoreBundle:Event\\Event')
                ->find($httpRequest->request->get('request'));
        $extraData['files'] = $httpRequest->files->all();

        return $extraData;
    }

    private function prepareExtraDataForApprove(Order $order, array $extraData, Instance $instance)
    {
        if (!$this->container->get('request_stack')->getCurrentRequest()->query->has('receive')) {
            $this->container->get('session')->getFlashBag()
                    ->add('error', 'There was an error changing the state.');

            throw new NotFoundHttpException();
        }

        $extraData['receive'] = $this->container
                ->get('doctrine.odm.mongodb.document_manager')
                ->getRepository('Celsius3CoreBundle:Event')
                ->find($this->container->get('request_stack')->getCurrentRequest()->query->get('receive'));

        if (!$extraData['receive']) {
            throw new NotFoundHttpException();
        }

        return $extraData;
    }

    private function prepareExtraDataForReclaim(Order $order, array $extraData, Instance $instance)
    {
        if (!$this->container->get('request_stack')->getCurrentRequest()->query->has('receive')) {
            $this->container->get('session')->getFlashBag()
                    ->add('error', 'There was an error changing the state.');

            throw new NotFoundHttpException();
        }

        $receive = $this->container
                ->get('doctrine.odm.mongodb.document_manager')
                ->getRepository('Celsius3CoreBundle:Event')
                ->find($this->container->get('request_stack')->getCurrentRequest()->query->get('receive'));

        if (!$receive) {
            $this->container->get('session')->getFlashBag()
                    ->add('error', 'There was an error changing the state.');

            throw new NotFoundHttpException();
        }

        $form = $this->container->get('form.factory')
                ->create(new OrderReclaimType());
        $request = $this->container->get('request_stack')->getCurrentRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $extraData['observations'] = $data['observations'];
            $extraData['request'] = $receive->getRequestEvent();
        } else {
            $this->container->get('session')->getFlashBag()
                    ->add('error', 'There was an error changing the state.');

            throw new NotValidException();
        }

        return $extraData;
    }

    private function prepareExtraDataForCancel(Order $order, array $extraData, Instance $instance)
    {
        if ($this->container->get('request_stack')->getCurrentRequest()->query->has('request')) {
            $extraData['request'] = $this->container
                    ->get('doctrine.odm.mongodb.document_manager')
                    ->getRepository('Celsius3CoreBundle:Event')
                    ->find(
                    $this->container->get('request_stack')->getCurrentRequest()->query
                    ->get('request'));

            $this->container->get('request_stack')->getCurrentRequest()->query->remove('request');
            if (!$extraData['request']) {
                throw new NotFoundHttpException();
            }
        } else {
            $extraData['httprequest'] = $this->container->get('request_stack')->getCurrentRequest();
            if ($order->getInstance()->getId() != $instance->getId()) {
                $extraData['remoterequest'] = $order
                        ->getState(StateManager::STATE__CREATED, $instance)
                        ->getRemoteEvent();
            }
            $extraData['sirequests'] = $this->container
                    ->get('doctrine.odm.mongodb.document_manager')
                    ->getRepository('Celsius3CoreBundle:SingleInstanceRequest')
                    ->findBy(
                    array('order.id' => $order->getId(),
                        'isCancelled' => false,
                        'instance.id' => $instance->getId(),));
            $extraData['mirequests'] = $this->container
                    ->get('doctrine.odm.mongodb.document_manager')
                    ->getRepository('Celsius3CoreBundle:MultiInstanceRequest')
                    ->findBy(
                    array('order.id' => $order->getId(),
                        'isCancelled' => false,
                        'instance.id' => $instance->getId(),));
        }
        return $extraData;
    }

    private function prepareExtraDataForAnnul(Order $order, array $extraData, Instance $instance)
    {
        if ($order->getInstance()->getId() != $instance->getId()) {
            $extraData['request'] = $order
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
                $event = ($extraData['request']->getRequest()->getInstance()->getId() != $extraData['request']->getInstance()->getId()) ? self::EVENT__MULTI_INSTANCE_RECEIVE : self::EVENT__SINGLE_INSTANCE_RECEIVE;
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

    public function cancelRequests($requests, \Symfony\Component\HttpFoundation\Request $httpRequest)
    {
        foreach ($requests as $request) {
            $httpRequest->query->set('request', $request->getId());
            $this->container->get('celsius3_core.lifecycle_helper')
                    ->createEvent(self::EVENT__CANCEL, $request->getOrder());
        }
    }

    public function getEvents($event, $request_id)
    {
        $dm = $this->container->get('doctrine.odm.mongodb.document_manager');

        if ($event === self::EVENT__REQUEST) {
            $repositories = array(
                $this->event_classes[self::EVENT__MULTI_INSTANCE_REQUEST],
                $this->event_classes[self::EVENT__SINGLE_INSTANCE_REQUEST],
            );
        } else if ($event === self::EVENT__RECEIVE) {
            $repositories = array(
                $this->event_classes[self::EVENT__MULTI_INSTANCE_RECEIVE],
                $this->event_classes[self::EVENT__SINGLE_INSTANCE_RECEIVE],
            );
        } else {
            $repositories = array(
                $this->event_classes[$event],
            );
        }

        $results = array();

        foreach ($repositories as $repository) {
            $results = array_merge($results, $dm->getRepository('Celsius3CoreBundle:Event\\' . $repository)
                            ->findBy(array('request.id' => $request_id)));
        }

        return $results;
    }

}
