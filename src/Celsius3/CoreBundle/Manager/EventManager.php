<?php

namespace Celsius3\CoreBundle\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Celsius3\CoreBundle\Document\Event\MultiInstanceRequest;
use Celsius3\CoreBundle\Document\Institution;
use Celsius3\CoreBundle\Document\Order;
use Celsius3\CoreBundle\Document\Request;
use Celsius3\CoreBundle\Exception\NotFoundException;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Form\Type\OrderReclaimType;

/**
 * @todo Eliminar el parámetro $instance de los prepareExtraDataFor*
 */
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
        self::EVENT__DELIVER => 'DeliverEvent',
        self::EVENT__CANCEL => 'CancelEvent',
        self::EVENT__LOCAL_CANCEL => 'LocalCancelEvent',
        self::EVENT__REMOTE_CANCEL => 'RemoteCancelEvent',
        self::EVENT__ANNUL => 'AnnulEvent',
        self::EVENT__TAKE => 'TakeEvent',
        self::EVENT__UPLOAD => 'UploadEvent',
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

            throw new NotFoundHttpException();
        }

        return $extraData;
    }

    private function prepareExtraDataForRequest(Request $request, array $extraData, Instance $instance)
    {
        $httpRequest = $this->container->get('request_stack')->getCurrentRequest();

        $extraData['observations'] = $httpRequest->request->get('observations', null);

        $dm = $this->container->get('doctrine.odm.mongodb.document_manager');
        $provider = $dm->getRepository('Celsius3CoreBundle:Institution')
                ->find($httpRequest->request->get('provider'));

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
                ->get('doctrine.odm.mongodb.document_manager')
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

    private function prepareExtraDataForApprove(Request $request, array $extraData, Instance $instance)
    {
        $httpRequest = $this->container->get('request_stack')->getCurrentRequest();
        $dm = $this->container->get('doctrine.odm.mongodb.document_manager');
        if (!$httpRequest->request->has('receive')) {
            $this->container->get('session')->getFlashBag()
                    ->add('error', 'There was an error changing the state.');

            throw new NotFoundHttpException();
        }

        $extraData['receive'] = $dm->getRepository('Celsius3CoreBundle:Event\\Event')
                ->find($httpRequest->request->get('receive'));

        if (!$extraData['receive']) {
            throw new NotFoundHttpException();
        }

        return $extraData;
    }

    private function prepareExtraDataForReclaim(Request $request, array $extraData, Instance $instance)
    {
        $httpRequest = $this->container->get('request_stack')->getCurrentRequest();
        $dm = $this->container->get('doctrine.odm.mongodb.document_manager');
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

        $event = $dm->getRepository('Celsius3CoreBundle:Event\\Event')
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
        $dm = $this->container->get('doctrine.odm.mongodb.document_manager');

        if ($httpRequest->request->has('request')) {
            $extraData['request'] = $dm->getRepository('Celsius3CoreBundle:Event\\Event')
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
            $extraData['sirequests'] = $dm->getRepository('Celsius3CoreBundle:Event\\SingleInstanceRequestEvent')
                    ->findBy(array(
                'request.id' => $request->getId(),
                'isCancelled' => false,
                'instance.id' => $instance->getId(),
            ));
            $extraData['mirequests'] = $dm->getRepository('Celsius3CoreBundle:Event\\MultiInstanceRequestEvent')
                    ->findBy(array(
                'request.id' => $request->getId(),
                'isCancelled' => false,
                'instance.id' => $instance->getId(),
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

    public function cancelRequests($requests, \Symfony\Component\HttpFoundation\Request $httpRequest)
    {
        foreach ($requests as $request) {
            $httpRequest->query->set('request', $request->getId());
            $this->container->get('celsius3_core.lifecycle_helper')->createEvent(self::EVENT__CANCEL, $request->getRequest());
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
