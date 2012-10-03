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
        
        $order->setCurrentState($state);
        
        $this->dm->persist($order);

        return $state;
    }

    /**
     * Receives the event name and the order document and creates the appropiate
     * event and state
     * 
     * @param string $name The event name
     * @param Celsius\Celsius3Bundle\Document\Order $order The Order document 
     * 
     * @todo Get rid of the switch statement and implement something prettier
     */
    public function createEvent($name, Order $order)
    {
        switch ($name)
        {
            case 'creation':
                $this->setEventData(new Creation(), $order, 'created');
                break;
            case 'search':
                $this->setEventData(new Search(), $order, 'searched');
                break;
            case 'sirequest':
                $this->setEventData(new SingleInstanceRequest(), $order, 'requested');
                break;
            case 'receive':
                $this->setEventData(new Receive(), $order, 'received');
                break;
            case 'sideliver':
                $this->setEventData(new SingleInstanceDeliver(), $order, 'delivered');
                break;
            case 'cancel':
                $this->setEventData(new Cancel(), $order, 'canceled');
                break;
            case 'annul':
                $this->setEventData(new Annul(), $order, 'annuled');
                break;
        }
    }

}