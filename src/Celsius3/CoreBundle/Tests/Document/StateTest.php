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
use Celsius3\CoreBundle\Document\State;
use Celsius3\CoreBundle\Document\StateType;
use Celsius3\CoreBundle\Document\Creation;
use Celsius3\CoreBundle\Document\MultiInstanceRequest;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Document\Order;

class StateTest extends TestCase
{

    protected $state;
    protected $type;
    protected $event;
    protected $remoteEvent;
    protected $instance;
    protected $previous;
    protected $order;

    public function setUp()
    {
        parent::setUp();

        $this->state = new State();
        $this->type = new StateType();
        $this->event = new Creation();
        $this->remoteEvent = new MultiInstanceRequest();
        $this->instance = new Instance();
        $this->previous = new State();
        $this->order = new Order();
    }

    public function testGetDate()
    {
        $date = date('Y-m-d H:i:s');

        $this->state->setDate($date);

        $this->assertEquals($date, $this->state->getDate());
    }

    public function testGetType()
    {
        $this->state->setType($this->type);

        $this->assertEquals($this->type, $this->state->getType());
    }

    public function testGetEvents()
    {
        $this->state->addEvents($this->event);

        $this->assertContains($this->event, $this->state->getEvents());
    }

    public function testGetRemoteEvent()
    {
        $this->state->setRemoteEvent($this->remoteEvent);

        $this->assertEquals($this->remoteEvent, $this->state->getRemoteEvent());
    }

    public function testGetInstance()
    {
        $this->state->setInstance($this->instance);

        $this->assertEquals($this->instance, $this->state->getInstance());
    }

    public function testGetPrevious()
    {
        $this->state->setPrevious($this->previous);

        $this->assertEquals($this->previous, $this->state->getPrevious());
    }

    public function testGetOrder()
    {
        $this->state->setOrder($this->order);

        $this->assertEquals($this->order, $this->state->getOrder());
    }

    public function testGenerateState()
    {
        $date = date('Y-m-d H:i:s');

        $this->state->setDate($date);

        $this->assertNull($this->state->getId());

        $this->documentManager->persist($this->state);
        $this->documentManager->flush();

        $this->assertNotNull($this->state->getId());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->state, $this->type, $this->event, $this->remoteEvent, $this->instance, $this->previous, $this->order);
    }

}