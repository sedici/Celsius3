<?php

namespace Celsius\Celsius3Bundle\Helper;

use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Document\State;
use Celsius\Celsius3Bundle\Document\Event;
use Celsius\Celsius3Bundle\Document\Creation;
use Celsius\Celsius3Bundle\Document\Search;
use Celsius\Celsius3Bundle\Document\SingleInstanceRequest;
use Celsius\Celsius3Bundle\Document\Receive;
use Celsius\Celsius3Bundle\Document\SingleInstanceDeliver;
use Celsius\Celsius3Bundle\Document\Cancel;
use Celsius\Celsius3Bundle\Document\Annul;

class LifecycleHelper
{

    private $dm;

    public function __construct($dm)
    {
        $this->dm = $dm;
    }

    protected function setEventData(Event $event, Order $order, $state)
    {
        $date = date('Y-m-d H:i:s');

        $event->setDate($date);
        $event->setOperator($order->getOperator());
        $event->setInstance($order->getInstance());
        $event->setOrder($order);
        $event->setState($this->createState($state, $date, $order));

        $this->dm->persist($event);
        $this->dm->flush();
    }

    protected function createState($name, $date, $order)
    {
        $state = new State();
        $state->setDate($date);
        $state->setInstance($order->getInstance());
        $state->setOrder($order);
        $state->setType(
                $this->dm->getRepository('CelsiusCelsius3Bundle:StateType')
                        ->createQueryBuilder()
                        ->field('name')->equals($name)
                        ->getQuery()
                        ->getSingleResult()
        );

        $this->dm->persist($state);

        return $state;
    }

    public function creation($order)
    {
        $this->setEventData(new Creation(), $order, 'created');
    }

    public function search(Order $order)
    {
        $this->setEventData(new Search(), $order, 'searched');
    }

    public function request(Order $order)
    {
        $this->setEventData(new SingleInstanceRequest(), $order, 'requested');
    }

    public function receive(Order $order)
    {
        $this->setEventData(new Receive(), $order, 'received');
    }

    public function deliver(Order $order)
    {
        $this->setEventData(new SingleInstanceDeliver(), $order, 'delivered');
    }

    public function cancel(Order $order)
    {
        $this->setEventData(new Cancel(), $order, 'canceled');
    }

    public function annul(Order $order)
    {
        $this->setEventData(new Annul(), $order, 'annuled');
    }

}