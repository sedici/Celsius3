<?php

namespace Celsius\Celsius3Bundle\Manager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Exception\NotValidException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Celsius\Celsius3Bundle\Document\Approve;
use Celsius\Celsius3Bundle\Document\Event;
use Celsius\Celsius3Bundle\Document\Institution;
use Celsius\Celsius3Bundle\Document\MultiInstanceRequest;
use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Document\SingleInstanceReceive;
use Celsius\Celsius3Bundle\Document\SingleInstanceRequest;
use Celsius\Celsius3Bundle\Form\Type\OrderRequestType;
use Celsius\Celsius3Bundle\Form\Type\OrderReceiveType;
use Celsius\Celsius3Bundle\Exception\NotFoundException;
use Symfony\Component\BrowserKit\Request;
use Celsius\Celsius3Bundle\Document\Instance;
use Celsius\Celsius3Bundle\Document\Reclaim;
use Celsius\Celsius3Bundle\Form\Type\OrderReclaimType;
use Celsius\Celsius3Bundle\Helper\LifecycleHelper;

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

    private $class_prefix = 'Celsius\\Celsius3Bundle\\Document\\';
    public $event_classes = array(self::EVENT__CREATION => 'Creation',
            self::EVENT__SEARCH => 'Search',
            self::EVENT__SINGLE_INSTANCE_REQUEST => 'SingleInstanceRequest',
            self::EVENT__MULTI_INSTANCE_REQUEST => 'MultiInstanceRequest',
            self::EVENT__APPROVE => 'Approve',
            self::EVENT__RECLAIM => 'Reclaim',
            self::EVENT__MULTI_INSTANCE_RECEIVE => 'MultiInstanceReceive',
            self::EVENT__SINGLE_INSTANCE_RECEIVE => 'SingleInstanceReceive',
            self::EVENT__DELIVER => 'Deliver', self::EVENT__CANCEL => 'Cancel',
            self::EVENT__LOCAL_CANCEL => 'LocalCancel',
            self::EVENT__REMOTE_CANCEL => 'RemoteCancel',
            self::EVENT__ANNUL => 'Annul',);
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

    public function createNotFoundException($message = 'Not Found',
            \Exception $previous = null)
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

    private function prepareExtraDataForRequest(Order $order, array $extraData,
            Instance $instance)
    {
        $document = new SingleInstanceRequest();
        $form = $this->container->get('form.factory')
                ->create(
                        new OrderRequestType(
                                $this->container
                                        ->get(
                                                'doctrine.odm.mongodb.document_manager'),
                                $this
                                        ->getFullClassNameForEvent(
                                                self::EVENT__SINGLE_INSTANCE_REQUEST)),
                        $document);
        $request = $this->container->get('request');
        $form->bind($request);

        if ($form->isValid()) {
            $extraData['provider'] = $document->getProvider();
            $extraData['observations'] = $document->getObservations();
        } else {
            $this->container->get('session')->getFlashBag()
                    ->add('error', 'There was an error changing the state.');

            throw new NotValidException();
        }

        return $extraData;
    }

    private function prepareExtraDataForReceive(Order $order, array $extraData,
            Instance $instance)
    {
        if (!$this->container->get('request')->query->has('request')) {
            $this->container->get('session')->getFlashBag()
                    ->add('error', 'There was an error changing the state.');

            throw new NotFoundHttpException();
        }

        $form = $this->container->get('form.factory')
                ->create(new OrderReceiveType());
        $request = $this->container->get('request');

        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $extraData['observations'] = $data['observations'];
            $extraData['request'] = $this->container
                    ->get('doctrine.odm.mongodb.document_manager')
                    ->getRepository('CelsiusCelsius3Bundle:Event')
                    ->find(
                            $this->container->get('request')->query
                                    ->get('request'));
            $extraData['files'] = $data['files'];
        } else {
            $this->container->get('session')->getFlashBag()
                    ->add('error', 'There was an error changing the state.');

            throw new NotValidException();
        }

        return $extraData;
    }

    private function prepareExtraDataForApprove(Order $order, array $extraData,
            Instance $instance)
    {
        if (!$this->container->get('request')->query->has('receive')) {
            $this->container->get('session')->getFlashBag()
                    ->add('error', 'There was an error changing the state.');

            throw new NotFoundHttpException();
        }

        $extraData['receive'] = $this->container
                ->get('doctrine.odm.mongodb.document_manager')
                ->getRepository('CelsiusCelsius3Bundle:Event')
                ->find($this->container->get('request')->query->get('receive'));

        if (!$extraData['receive']) {
            throw new NotFoundHttpException();
        }

        return $extraData;
    }

    private function prepareExtraDataForReclaim(Order $order, array $extraData,
            Instance $instance)
    {
        if (!$this->container->get('request')->query->has('receive')) {
            $this->container->get('session')->getFlashBag()
                    ->add('error', 'There was an error changing the state.');

            throw new NotFoundHttpException();
        }

        $receive = $this->container
                ->get('doctrine.odm.mongodb.document_manager')
                ->getRepository('CelsiusCelsius3Bundle:Event')
                ->find($this->container->get('request')->query->get('receive'));

        if (!$receive) {
            $this->container->get('session')->getFlashBag()
                    ->add('error', 'There was an error changing the state.');

            throw new NotFoundHttpException();
        }

        $form = $this->container->get('form.factory')
                ->create(new OrderReclaimType());
        $request = $this->container->get('request');

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

    private function prepareExtraDataForCancel(Order $order, array $extraData,
            Instance $instance)
    {
        if ($this->container->get('request')->query->has('request')) {
            $extraData['request'] = $this->container
                    ->get('doctrine.odm.mongodb.document_manager')
                    ->getRepository('CelsiusCelsius3Bundle:Event')
                    ->find(
                            $this->container->get('request')->query
                                    ->get('request'));

            $this->container->get('request')->query->remove('request');
            if (!$extraData['request']) {
                throw new NotFoundHttpException();
            }
        } else {
            $extraData['httprequest'] = $this->container->get('request');
            if ($order->getInstance()->getId() != $instance->getId()) {
                $extraData['remoterequest'] = $order
                        ->getState(StateManager::STATE__CREATED, $instance)
                        ->getRemoteEvent();
            }
            $extraData['sirequests'] = $this->container
                    ->get('doctrine.odm.mongodb.document_manager')
                    ->getRepository(
                            'CelsiusCelsius3Bundle:SingleInstanceRequest')
                    ->findBy(
                            array('order.id' => $order->getId(),
                                    'isCancelled' => false,
                                    'instance.id' => $instance->getId(),));
            $extraData['mirequests'] = $this->container
                    ->get('doctrine.odm.mongodb.document_manager')
                    ->getRepository(
                            'CelsiusCelsius3Bundle:MultiInstanceRequest')
                    ->findBy(
                            array('order.id' => $order->getId(),
                                    'isCancelled' => false,
                                    'instance.id' => $instance->getId(),));
        }
        return $extraData;
    }

    private function prepareExtraDataForAnnul(Order $order, array $extraData,
            Instance $instance)
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
            $event = ($extraData['provider'] instanceof Institution
                    && $extraData['provider']->getCelsiusInstance()) ? self::EVENT__MULTI_INSTANCE_REQUEST
                    : self::EVENT__SINGLE_INSTANCE_REQUEST;
            break;
        case self::EVENT__RECEIVE:
            $event = ($extraData['request']->getOrder()->getInstance()->getId()
                    != $extraData['request']->getInstance()->getId()) ? self::EVENT__MULTI_INSTANCE_RECEIVE
                    : self::EVENT__SINGLE_INSTANCE_RECEIVE;
            break;
        case self::EVENT__CANCEL:
            var_dump(array_key_exists('request', $extraData));
            $event = array_key_exists('request', $extraData) ? (($extraData['request'] instanceof MultiInstanceRequest) ? self::EVENT__REMOTE_CANCEL
                            : self::EVENT__LOCAL_CANCEL) : self::EVENT__CANCEL;
            break;
        default:
            ;
        }
        return $event;
    }

    public function prepareExtraData($event, Order $order, Instance $instance)
    {
        $methodName = 'prepareExtraDataFor' . ucfirst($event);
        return $this->$methodName($order, array(), $instance);
    }

    public function cancelRequests($requests,
            \Symfony\Component\HttpFoundation\Request $httpRequest)
    {
        foreach ($requests as $request) {
            $httpRequest->query->set('request', $request->getId());
            $this->container->get('lifecycle_helper')
                    ->createEvent(self::EVENT__CANCEL, $request->getOrder());
        }
    }

    /*
     * Event rendering functions
     */

    public function getDataForRequestRendering(Event $event, Order $order)
    {
        $instance = $this->container->get('instance_helper')
                ->getSessionInstance();

        return array('event' => $event,
                'isMultiInstance' => $event instanceof MultiInstanceRequest,
                'order' => $order,
                'receive_form' => $this->container->get('form.factory')
                        ->create(new OrderReceiveType(),
                                new SingleInstanceReceive())->createView(),
                'isReceived' => $this->container
                        ->get('doctrine.odm.mongodb.document_manager')
                        ->getRepository('CelsiusCelsius3Bundle:Event')
                        ->findBy(array('requestEvent.id' => $event->getId()))
                        ->count() > 0,
                'isDelivered' => $order
                        ->getState(StateManager::STATE__DELIVERED, $instance),
                'isCancelled' => $order
                        ->hasState(StateManager::STATE__CANCELLED, $instance),);
    }

    public function getDataForReceiveRendering(Event $event, Order $order)
    {
        $instance = $this->container->get('instance_helper')
                ->getSessionInstance();
        $isApproveEvent = false;
        if ($event instanceof Approve) {
            $event = $event->getState()->getPrevious()->getRemoteEvents()
                    ->filter(
                            function ($entry) use ($event)
                            {
                                return ($entry->getId()
                                        == $event->getReceiveEvent()->getId());
                            })->first();
            $isApproveEvent = true;
        }

        $isMultiInstance = $event->getInstance() != $instance;
        if ($isMultiInstance) {
            $provider = $order
                    ->getState(StateManager::STATE__CREATED,
                            $event->getInstance())->getRemoteEvent()
                    ->getProvider();
        } else {
            $provider = $event->getRequestEvent()->getProvider();
        }

        return array('event' => $event, 'provider' => $provider,
                'reclaim_form' => $this->container->get('form.factory')
                        ->create(new OrderReclaimType(), new Reclaim())
                        ->createView(), 'isMultiInstance' => $isMultiInstance,
                'order' => $order,
                'isDelivered' => $order
                        ->getState(StateManager::STATE__DELIVERED, $instance),
                'isReclaimed' => $event->getIsReclaimed(),
                'isApproveEvent' => $isApproveEvent,
                'isApproved' => $event->getInstance()->getId()
                        != $instance->getId() ? ($this->container
                                ->get('doctrine.odm.mongodb.document_manager')
                                ->getRepository('CelsiusCelsius3Bundle:Approve')
                                ->findBy(
                                        array(
                                                'receiveEvent.id' => $event
                                                        ->getId()))->count()
                                > 0) : true,);
    }

}
