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
use Celsius3\CoreBundle\Document\StateType;
use Celsius3\CoreBundle\Document\State;

class StateTypeTest extends TestCase
{

    protected $stateType;
    protected $state;

    public function setUp()
    {
        parent::setUp();

        $this->stateType = new StateType();
        $this->state = new State();
    }

    public function testGetName()
    {
        $name = 'Sample name';

        $this->stateType->setName($name);

        $this->assertEquals($name, $this->stateType->getName());
    }

    public function testGetStates()
    {
        $this->stateType->addStates($this->state);

        $this->assertContains($this->state, $this->stateType->getStates());
    }

    public function testGenerateState()
    {
        $name = 'Sample name';

        $this->stateType->setName($name);

        $this->assertNull($this->stateType->getId());

        $this->documentManager->persist($this->stateType);
        $this->documentManager->flush();

        $this->assertNotNull($this->stateType->getId());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->stateType, $this->state);
    }

}