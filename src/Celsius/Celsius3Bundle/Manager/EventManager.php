<?php

namespace Celsius\Celsius3Bundle\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Exception\NotValidException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Celsius\Celsius3Bundle\Document\Institution;
use Celsius\Celsius3Bundle\Document\Order;
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
    const EVENT__RECEIVE = 'receive';
    const EVENT__SINGLE_INSTANCE_DELIVER = 'sideliver';
    const EVENT__MULTI_INSTANCE_DELIVER = 'mideliver';
    const EVENT__CANCEL = 'cancel';
    const EVENT__ANNUL = 'annul';
    
    // Fake events
    const EVENT__REQUEST = 'request';
    const EVENT__DELIVER = 'deliver';

    private $class_prefix = 'Celsius\\Celsius3Bundle\\Document\\';
    public $event_classes = array(
        self::EVENT__CREATION => 'Creation',
        self::EVENT__SEARCH => 'Search',
        self::EVENT__SINGLE_INSTANCE_REQUEST => 'SingleInstanceRequest',
        self::EVENT__MULTI_INSTANCE_REQUEST => 'MultiInstanceRequest',
        self::EVENT__APPROVE => 'Approve',
        self::EVENT__RECLAIM => 'Reclaim',
        self::EVENT__RECEIVE => 'Receive',
        self::EVENT__SINGLE_INSTANCE_DELIVER => 'SingleInstanceDeliver',
        self::EVENT__MULTI_INSTANCE_DELIVER => 'MultiInstanceDeliver',
        self::EVENT__CANCEL => 'Cancel',
        self::EVENT__ANNUL => 'Annul',
    );
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function createNotFoundException($message = 'Not Found', \Exception $previous = null)
    {
        return new NotFoundException($message, $previous);
    }

    public function getClassNameForEvent($event)
    {
        if (!array_key_exists($event, $this->event_classes))
        {
            throw $this->createNotFoundException('Event not found.');
        }

        return $this->event_classes[$event];
    }

    public function getFullClassNameForEvent($event)
    {
        if (!array_key_exists($event, $this->event_classes))
        {
            throw $this->createNotFoundException('Event not found.');
        }

        return $this->class_prefix . $this->event_classes[$event];
    }

    private function prepareExtraDataForRequest(Order $order, array $extraData)
    {
        $document = new SingleInstanceRequest();
        $form = $this->container->get('form.factory')->create(new OrderRequestType($this->container->get('doctrine.odm.mongodb.document_manager'), $this->getFullClassNameForEvent(self::EVENT__SINGLE_INSTANCE_REQUEST)), $document);
        $request = $this->container->get('request');
        $form->bind($request);

        if ($form->isValid())
        {
            $extraData['provider'] = $document->getProvider();
        } else
        {
            $this->container->get('session')->getFlashBag()->add('error', 'There was an error changing the state.');

            throw new NotValidException();
        }

        return $extraData;
    }

    private function prepareExtraDataForReceive(Order $order, array $extraData)
    {
        if (!$this->container->get('request')->query->has('request'))
        {
            $this->container->get('session')->getFlashBag()->add('error', 'There was an error changing the state.');

            throw new NotFoundHttpException();
        }

        $form = $this->container->get('form.factory')->create(new OrderReceiveType());
        $request = $this->container->get('request');

        $form->bind($request);

        if ($form->isValid())
        {
            $data = $form->getData();
            $extraData['request'] = $this->container->get('request')->query->get('request');
            $extraData['files'] = $data['files'];
        } else
        {
            $this->container->get('session')->getFlashBag()->add('error', 'There was an error changing the state.');

            throw new NotValidException();
        }

        return $extraData;
    }

    private function prepareExtraDataForDeliver(Order $order, array $extraData)
    {
        if (!$this->container->get('request')->query->has('receive'))
        {
            $this->container->get('session')->getFlashBag()->add('error', 'There was an error changing the state.');

            throw new NotFoundHttpException();
        }

        $extraData['receive'] = $this->container->get('doctrine.odm.mongodb.document_manager')
                ->getRepository('CelsiusCelsius3Bundle:Receive')
                ->find($this->container->get('request')->query->get('receive'));

        if (!$extraData['receive'])
        {
            throw new NotFoundHttpException();
        }

        return $extraData;
    }

    public function getRealEventName($event, array $extraData)
    {
        switch ($event)
        {
            case self::EVENT__REQUEST: $event = ($extraData['provider'] instanceof Institution && $extraData['provider']->getCelsiusInstance()) ? self::EVENT__MULTI_INSTANCE_REQUEST : self::EVENT__SINGLE_INSTANCE_REQUEST;
                break;
            case self::EVENT__DELIVER: $event = ($extraData['receive']->getRequestEvent() instanceof SingleInstanceRequest ? self::EVENT__SINGLE_INSTANCE_DELIVER : self::EVENT__MULTI_INSTANCE_DELIVER);
                break;
            default:;
        }
        return $event;
    }

    public function prepareExtraData($event, Order $order)
    {
        $extraData = array();
        switch ($event)
        {
            case self::EVENT__REQUEST: $extraData = $this->prepareExtraDataForRequest($order, $extraData);
                break;
            case self::EVENT__RECEIVE: $extraData = $this->prepareExtraDataForReceive($order, $extraData);
                break;
            case self::EVENT__DELIVER: $extraData = $this->prepareExtraDataForDeliver($order, $extraData);
                break;
            default:;
        }
        return $extraData;
    }

}