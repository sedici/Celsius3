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
use Celsius3\CoreBundle\Document\Configuration;
use Celsius3\CoreBundle\Document\Instance;

class ConfigurationTest extends TestCase
{
    protected $configuration;
    protected $instance;

    public function setUp()
    {
        parent::setUp();

        $this->configuration = new Configuration();
        $this->instance = new Instance();
    }

    public function testGetKey()
    {
        $key = 'testkey';

        $this->configuration->setKey($key);

        $this->assertEquals($key, $this->configuration->getKey());
    }

    public function testGetValue()
    {
        $value = 'testvalue';

        $this->configuration->setValue($value);

        $this->assertEquals($value, $this->configuration->getValue());
    }

    public function testGetName()
    {
        $name = 'Test Name';

        $this->configuration->setName($name);

        $this->assertEquals($name, $this->configuration->getName());
    }

    public function testGetType()
    {
        $type = 'boolean';

        $this->configuration->setType($type);

        $this->assertEquals($type, $this->configuration->getType());
    }

    public function testGetInstance()
    {
        $this->configuration->setInstance($this->instance);

        $this->assertEquals($this->instance, $this->configuration->getInstance());
    }

    public function testGenerateConfiguration()
    {
        $key = 'testkey';
        $value = 'testvalue';
        $name = 'Sample Name';
        $type = 'boolean';

        $this->configuration->setKey($key);
        $this->configuration->setValue($value);
        $this->configuration->setName($name);
        $this->configuration->setType($type);

        $this->documentManager->persist($this->configuration);
        $this->documentManager->flush();

        $this->assertNotNull($this->configuration->getId());
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->configuration);
        unset($this->instance);
    }
}