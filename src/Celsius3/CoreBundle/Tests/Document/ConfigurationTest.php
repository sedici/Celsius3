<?php

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
