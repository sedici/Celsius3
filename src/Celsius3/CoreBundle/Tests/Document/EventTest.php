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

namespace Celsius3\CoreBundle\Tests\Document;

use Celsius3\CoreBundle\Tests\TestCase;
use Celsius3\CoreBundle\Document\Order;
use Celsius3\CoreBundle\Document\Admin;
use Celsius3\CoreBundle\Document\State;
use Celsius3\CoreBundle\Document\Instance;

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