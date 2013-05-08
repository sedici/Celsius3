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

    private function prepareExtraDataForRequest(Order $order, array $extraData)
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

    private function prepareExtraDataForReceive(Order $order, array $extraData)
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

    private function prepareExtraDataForApprove(Order $order, array $extraData)
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

    private function prepareExtraDataForDeliver(Order $order, array $extraData)
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
        default:
            ;
        }
        return $event;
    }

    public function prepareExtraData($event, Order $order)
    {
        $methodName = 'prepareExtraDataFor' . ucfirst($event);
        return $this->$methodName($order, array());
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
                        ->getState(StateManager::STATE__DELIVERED, $instance),);
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

        return array('event' => $event,
                'isMultiInstance' => $event->getRequestEvent() instanceof MultiInstanceRequest,
                'order' => $order,
                'isDelivered' => $order
                        ->getState(StateManager::STATE__DELIVERED, $instance),
                'isReclaimed' => $event->getReclaimed(),
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
