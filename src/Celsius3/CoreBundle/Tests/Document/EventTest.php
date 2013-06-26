<?php

namespace Celsius\Celsius3Bundle\Tests\Document;

use Celsius\Celsius3Bundle\Tests\TestCase;
use Celsius\Celsius3Bundle\Document\Order;
use Celsius\Celsius3Bundle\Document\Admin;
use Celsius\Celsius3Bundle\Document\State;
use Celsius\Celsius3Bundle\Document\Instance;

class EventTest extends TestCase
{

    protected $event;
    protected $order;
    protected $operator;
    protected $state;
    protected $instance;

    public function setUp()
    {
        parent::setUp();

        $this->event = $this->getMockForAbstractClass('Celsius\\Celsius3Bundle\\Document\\Event');
        $this->order = new Order();
        $this->operator = new Admin();
        $this->state = new State();
        $this->instance = new Instance();
    }

    public function testGetId()
    {
        $this->assertNull($this->event->getId());
    }

    public function testGetDate()
    {
        $date = date('Y-m-d H:i:s');

        $this->event->setDate($date);

        $this->assertEquals($date, $this->event->getDate());
    }

    public function testGetOrder()
    {
        $this->event->setOrder($this->order);

        $this->assertEquals($this->order, $this->event->getOrder());
    }

    public function testGetOperator()
    {
        $this->event->setOperator($this->operator);

        $this->assertEquals($this->operator, $this->event->getOperator());
    }

    public function testGetState()
    {
        $this->event->setState($this->state);

        $this->assertEquals($this->state, $this->event->getState());
    }

    public function testGetInstance()
    {
        $this->event->setInstance($this->instance);

        $this->assertEquals($this->instance, $this->event->getInstance());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->event, $this->order, $this->operator, $this->state, $this->instance);
    }

}