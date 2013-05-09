<?php

namespace Celsius\Celsius3Bundle\Manager;
use Symfony\Component\Form\FormFactoryInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius\Celsius3Bundle\Exception\NotFoundException;
use Celsius\Celsius3Bundle\Manager\EventManager;
use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Document\SingleInstanceRequest;
use Celsius\Celsius3Bundle\Form\Type\OrderRequestType;
use Celsius\Celsius3Bundle\Helper\InstanceHelper;

class StateManager
{
    const STATE__CREATED = 'created';
    const STATE__SEARCHED = 'searched';
    const STATE__REQUESTED = 'requested';
    const STATE__APPROVAL_PENDING = 'approval_pending';
    const STATE__RECEIVED = 'received';
    const STATE__DELIVERED = 'delivered';
    const STATE__CANCELED = 'canceled';
    const STATE__ANNULED = 'annuled';

    private $graph = array(
            self::STATE__CREATED => array('positive' => true,
                    'mandatory' => true,
                    'events' => array(
                            EventManager::EVENT__SEARCH => array(
                                    'weight' => 10,
                                    'destinationState' => self::STATE__SEARCHED,),
                            EventManager::EVENT__ANNUL => array('weight' => 1,
                                    'destinationState' => self::STATE__ANNULED,),),
                    'previousStates' => array(),
                    'originatingEvents' => array(EventManager::EVENT__CREATION,),),
            self::STATE__SEARCHED => array('positive' => true,
                    'mandatory' => true,
                    'events' => array(
                            EventManager::EVENT__MULTI_INSTANCE_REQUEST => array(
                                    'weight' => 10,
                                    'destinationState' => self::STATE__REQUESTED,
                                    'remoteState' => self::STATE__CREATED,),
                            EventManager::EVENT__SINGLE_INSTANCE_REQUEST => array(
                                    'weight' => 9,
                                    'destinationState' => self::STATE__REQUESTED,),
                            EventManager::EVENT__CANCEL => array(
                                    'weight' => 2,
                                    'destinationState' => self::STATE__CANCELED,),
                            EventManager::EVENT__ANNUL => array('weight' => 1,
                                    'destinationState' => self::STATE__ANNULED,),),
                    'previousStates' => array(self::STATE__CREATED,),
                    'originatingEvents' => array(EventManager::EVENT__SEARCH,),),
            self::STATE__REQUESTED => array('positive' => true,
                    'mandatory' => true,
                    'events' => array(
                            EventManager::EVENT__SINGLE_INSTANCE_RECEIVE => array(
                                    'weight' => 10,
                                    'destinationState' => self::STATE__RECEIVED,),
                            EventManager::EVENT__MULTI_INSTANCE_RECEIVE => array(
                                    'weight' => 9,
                                    'destinationState' => self::STATE__RECEIVED,
                                    'remoteState' => self::STATE__APPROVAL_PENDING,),
                            EventManager::EVENT__CANCEL => array(
                                    'weight' => 2,
                                    'destinationState' => self::STATE__CANCELED,),),
                    'previousStates' => array(self::STATE__SEARCHED,
                            self::STATE__APPROVAL_PENDING,),
                    'originatingEvents' => array(
                            EventManager::EVENT__MULTI_INSTANCE_REQUEST,
                            EventManager::EVENT__SINGLE_INSTANCE_REQUEST,
                            EventManager::EVENT__RECLAIM,),),
            self::STATE__APPROVAL_PENDING => array('positive' => true,
                    'mandatory' => false,
                    'events' => array(
                            EventManager::EVENT__APPROVE => array(
                                    'weight' => 10,
                                    'destinationState' => self::STATE__RECEIVED,),
                            EventManager::EVENT__RECLAIM => array(
                                    'weight' => 2,
                                    'destinationState' => self::STATE__REQUESTED,),
                            EventManager::EVENT__CANCEL => array(
                                    'weight' => 1,
                                    'destinationState' => self::STATE__CANCELED,),),
                    'previousStates' => array(self::STATE__REQUESTED,),
                    'originatingEvents' => array(
                            EventManager::EVENT__MULTI_INSTANCE_RECEIVE,),),
            self::STATE__RECEIVED => array('positive' => true,
                    'mandatory' => true,
                    'events' => array(
                            EventManager::EVENT__DELIVER => array(
                                    'weight' => 10,
                                    'destinationState' => self::STATE__DELIVERED,),),
                    'previousStates' => array(self::STATE__REQUESTED,
                            self::STATE__APPROVAL_PENDING,),
                    'originatingEvents' => array(
                            EventManager::EVENT__SINGLE_INSTANCE_RECEIVE,
                            EventManager::EVENT__APPROVE,),),
            self::STATE__DELIVERED => array('positive' => true,
                    'mandatory' => true, 'events' => array(),
                    'previousStates' => array(self::STATE__RECEIVED,),
                    'originatingEvents' => array(EventManager::EVENT__DELIVER,),),
            self::STATE__CANCELED => array('positive' => false,
                    'mandatory' => false, 'events' => array(),
                    'previousStates' => array(self::STATE__APPROVAL_PENDING,
                            self::STATE__REQUESTED, self::STATE__SEARCHED,
                    ),
                    'originatingEvents' => array(EventManager::EVENT__CANCEL,),),
            self::STATE__ANNULED => array('positive' => false,
                    'mandatory' => false, 'events' => array(),
                    'previousStates' => array(self::STATE__SEARCHED,
                            self::STATE__CREATED,),
                    'originatingEvents' => array(EventManager::EVENT__ANNUL,),),);
    private $event_manager;
    private $instance_helper;
    private $form_factory;
    private $document_manager;

    public function __construct(EventManager $event_manager,
            InstanceHelper $instance_helper, FormFactoryInterface $form_factory,
            DocumentManager $document_manager)
    {
        $this->event_manager = $event_manager;
        $this->instance_helper = $instance_helper;
        $this->form_factory = $form_factory;
        $this->document_manager = $document_manager;
    }

    public function createNotFoundException($message = 'Not Found',
            \Exception $previous = null)
    {
        return new NotFoundException($message, $previous);
    }

    public function getStateForEvent($event)
    {
        if (!array_key_exists($event, $this->event_manager->event_classes)) {
            throw $this->createNotFoundException('Event not found.');
        }

        $data = null;

        foreach ($this->graph as $state) {
            if ($state['positive']) {
                foreach ($state['events'] as $key => $ev) {
                    if ($key == $event) {
                        $data = $ev['destinationState'];
                    }
                }
            }
        }

        if ($data == null && $event == EventManager::EVENT__CREATION) {
            $data = self::STATE__CREATED;
        }

        return $data;
    }

    public function getPositiveStates()
    {
        return array_filter($this->graph,
                function ($value)
                {
                    return $value['positive'];
                });
    }

    public function getStateData($state)
    {
        if (!array_key_exists($state, $this->graph)) {
            throw $this->createNotFoundException('State not found.');
        }

        return $this->graph[$state];
    }

    public function getEventsToState($state)
    {
        if (!array_key_exists($state, $this->graph)) {
            throw $this->createNotFoundException('State not found.');
        }

        $data = array();

        foreach ($this->graph[$state]['previousStates'] as $previous) {
            foreach ($this->graph[$previous]['events'] as $key => $event) {
                if ($event['destinationState'] == $state) {
                    $data[$key] = $event;
                }
            }
        }

        return $data;
    }

    public function getPreviousPositiveState($state)
    {
        if (!array_key_exists($state, $this->graph)) {
            throw $this->createNotFoundException('State not found.');
        }

        $data = null;

        if (count($this->graph[$state]['previousStates']) == 0) {
            $data = $state;
        } else {
            foreach ($this->graph[$state]['previousStates'] as $previous) {
                if ($this->graph[$previous]['positive']) {
                    $data = $previous;
                }
            }
        }

        return $data;
    }

    public function getPreviousMandatoryState($state)
    {
        if (!array_key_exists($state, $this->graph)) {
            throw $this->createNotFoundException('State not found.');
        }

        $data = null;

        if (count($this->graph[$state]['previousStates']) == 0) {
            $data = $state;
        } else {
            foreach ($this->graph[$state]['previousStates'] as $previous) {
                if ($this->graph[$previous]['mandatory']) {
                    $data = $previous;
                }
            }
        }

        return $data;
    }

    /*
     * State rendering functions
     */

    public function getDataForHeaderRendering($state, Order $order)
    {
        $instance = $this->instance_helper->getSessionInstance();

        return array('state' => $state, 'order' => $order,
                'hasState' => $order->hasState($state, $instance),
                'hasPrevious' => $order
                        ->hasState($this->getPreviousMandatoryState($state),
                                $instance), 'instance' => $instance,);
    }

    public function getDataForBodyRendering($state, Order $order)
    {
        $instance = $this->instance_helper->getSessionInstance();
        $requestForm = self::STATE__REQUESTED == $state ? $this->form_factory
                        ->create(
                                new OrderRequestType($this->document_manager,
                                        $this->event_manager
                                                ->getFullClassNameForEvent(
                                                        EventManager::EVENT__SINGLE_INSTANCE_REQUEST)),
                                new SingleInstanceRequest())->createView()
                : null;

        return array('state' => $state, 'order' => $order,
                'events' => $this->getEventsToState($state),
                'hasState' => $order->hasState($state, $instance),
                'hasPrevious' => $order
                        ->hasState($this->getPreviousMandatoryState($state),
                                $instance),
                'isCurrent' => $order->getCurrentState($instance)->getType()
                        ->getName() == $state, 'request_form' => $requestForm,
                'isDelivered' => $order
                        ->getState(self::STATE__DELIVERED, $instance),
                'instance' => $instance,);
    }
}
