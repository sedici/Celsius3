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
use Celsius3\CoreBundle\Document\Counter;

class CounterTest extends TestCase
{

    protected $counter;

    public function setUp()
    {
        parent::setUp();

        $this->counter = new Counter();
    }

    public function testGetName()
    {
        $name = 'Sample name';

        $this->counter->setName($name);

        $this->assertEquals($name, $this->counter->getName());
    }

    public function testGetValue()
    {
        $value = 0;

        $this->counter->setValue($value);

        $this->assertEquals($value, $this->counter->getValue());
    }

    public function testGenerateConfiguration()
    {
        $name = 'Sample name';
        $value = 0;

        $this->counter->setName($name);
        $this->counter->setValue($value);

        $this->assertNull($this->counter->getId());

        $this->documentManager->persist($this->counter);
        $this->documentManager->flush();

        $this->assertNotNull($this->counter->getId());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->counter);
    }

}